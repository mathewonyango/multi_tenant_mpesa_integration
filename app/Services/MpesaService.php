<?php

namespace App\Services;

use App\Models\Hotel;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MpesaService
{
    private $consumerKey;
    private $consumerSecret;
    private $shortcode;
    private $passkey;
    private $environment;
    
    public function __construct(?Hotel $hotel = null)
    {
        if ($hotel && $hotel->payment_integration_type === 'direct') {
            $this->consumerKey = $hotel->mpesa_consumer_key;
            $this->consumerSecret = $hotel->mpesa_consumer_secret;
            $this->shortcode = $hotel->mpesa_shortcode;
            $this->passkey = $hotel->mpesa_passkey;
            $this->environment = $hotel->mpesa_environment;
        } else {
            $this->consumerKey = config('mpesa.consumer_key');
            $this->consumerSecret = config('mpesa.consumer_secret');
            $this->shortcode = config('mpesa.shortcode');
            $this->passkey = config('mpesa.passkey');
            $this->environment = config('mpesa.environment');
        }
    }
    
    private function getBaseUrl(): string
    {
        return $this->environment === 'production'
            ? 'https://api.safaricom.co.ke'
            : 'https://sandbox.safaricom.co.ke';
    }
    
    private function getAccessToken(): ?string
    {
        try {
            $url = $this->getBaseUrl() . '/oauth/v1/generate?grant_type=client_credentials';
            
            $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)->get($url);
            
            if ($response->successful()) {
                return $response->json()['access_token'];
            }
            
            Log::error('M-PESA token failed', ['response' => $response->json()]);
            return null;
        } catch (\Exception $e) {
            Log::error('M-PESA token exception', ['error' => $e->getMessage()]);
            return null;
        }
    }
    
    public function stkPush(Order $order, string $phoneNumber, float $amount): array
    {
        $token = $this->getAccessToken();
        
        if (!$token) {
            return ['success' => false, 'message' => 'Failed to generate token'];
        }
        
        $phoneNumber = $this->formatPhoneNumber($phoneNumber);
        $timestamp = date('YmdHis');
        $password = base64_encode($this->shortcode . $this->passkey . $timestamp);
        
        $url = $this->getBaseUrl() . '/mpesa/stkpush/v1/processrequest';
        
        $payload = [
            'BusinessShortCode' => $this->shortcode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => round($amount),
            'PartyA' => $phoneNumber,
            'PartyB' => $this->shortcode,
            'PhoneNumber' => $phoneNumber,
            'CallBackURL' => config('mpesa.callback_url'),
            'AccountReference' => 'Order' . $order->id,
            'TransactionDesc' => 'Payment for Order #' . $order->id
        ];
        
        try {
            $response = Http::withToken($token)->post($url, $payload);
            $responseData = $response->json();
            
            if ($response->successful() && isset($responseData['CheckoutRequestID'])) {
                $order->update([
                    'mpesa_checkout_request_id' => $responseData['CheckoutRequestID'],
                    'status' => 'pending'
                ]);
                
                return [
                    'success' => true,
                    'checkout_request_id' => $responseData['CheckoutRequestID'],
                    'message' => 'STK push sent successfully'
                ];
            }
            
            Log::error('STK Push failed', ['response' => $responseData]);
            return ['success' => false, 'message' => $responseData['errorMessage'] ?? 'STK push failed'];
            
        } catch (\Exception $e) {
            Log::error('STK Push exception', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Payment processing error'];
        }
    }
    
    private function formatPhoneNumber(string $phone): string
    {
        $phone = preg_replace('/[\s\-\+]/', '', $phone);
        
        if (substr($phone, 0, 1) === '0') {
            $phone = '254' . substr($phone, 1);
        }
        
        if (substr($phone, 0, 3) !== '254') {
            $phone = '254' . $phone;
        }
        
        return $phone;
    }
}