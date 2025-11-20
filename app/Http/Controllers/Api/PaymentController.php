<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\HotelPayout;
use App\Services\MpesaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function initiatePayment(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'phone_number' => 'required|string'
        ]);
        
        try {
            $order = Order::with('hotel')->findOrFail($validated['order_id']);
            
            if ($order->status === 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Order already paid'
                ], 400);
            }
            
            $mpesaService = new MpesaService($order->hotel);
            
            $result = $mpesaService->stkPush(
                $order,
                $validated['phone_number'],
                $order->amount
            );
            
            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment request sent. Check your phone.',
                    'checkout_request_id' => $result['checkout_request_id']
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 400);
            
        } catch (\Exception $e) {
            Log::error('Payment initiation error', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to initiate payment'
            ], 500);
        }
    }
    
    public function callback(Request $request)
    {
        // dd($request)
        Log::info('M-PESA Callback', $request->all());
        
        try {
            $callbackData = $request->all();
            $resultCode = $callbackData['Body']['stkCallback']['ResultCode'];
            $checkoutRequestId = $callbackData['Body']['stkCallback']['CheckoutRequestID'];
            
            $order = Order::where('mpesa_checkout_request_id', $checkoutRequestId)->first();
            
            if (!$order) {
                Log::warning('Order not found', ['checkout_request_id' => $checkoutRequestId]);
                return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
            }
            
            DB::beginTransaction();
            
            try {
                if ($resultCode == 0) {
                    $callbackMetadata = $callbackData['Body']['stkCallback']['CallbackMetadata']['Item'];
                    $mpesaTransactionId = null;
                    $phoneNumber = null;
                    
                    foreach ($callbackMetadata as $item) {
                        if ($item['Name'] === 'MpesaReceiptNumber') {
                            $mpesaTransactionId = $item['Value'];
                        }
                        if ($item['Name'] === 'PhoneNumber') {
                            $phoneNumber = $item['Value'];
                        }
                    }
                    
                    $order->update([
                        'status' => 'completed',
                        'mpesa_transaction_id' => $mpesaTransactionId,
                        'customer_phone' => $phoneNumber,
                        'paid_at' => now()
                    ]);
                    
                    // For Type B hotels, track for payout
                    $hotel = $order->hotel;
                    if ($hotel->payment_integration_type === 'aggregated') {
                        // Payout will be processed later by cron job
                        Log::info('Type B payment tracked for payout', ['order_id' => $order->id]);
                    }
                    
                    Log::info('Payment completed', [
                        'order_id' => $order->id,
                        'transaction_id' => $mpesaTransactionId
                    ]);
                    
                } else {
                    $order->update(['status' => 'failed']);
                    
                    Log::warning('Payment failed', [
                        'order_id' => $order->id,
                        'result_code' => $resultCode
                    ]);
                }
                
                DB::commit();
                
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Callback processing error', ['error' => $e->getMessage()]);
            }
            
            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
            
        } catch (\Exception $e) {
            Log::error('Callback exception', ['error' => $e->getMessage()]);
            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
        }
    }
    
   public function checkStatus($orderId)
{
    try {
        // Fetch order with hotel
        $order = Order::with('hotel')->find($orderId);

        // Order not found
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => "Order not found",
                'code' => 404,
            ], 404);
        }

        // Hotel missing (rare but possible if deleted)
        if (!$order->hotel) {
            return response()->json([
                'success' => false,
                'message' => "Hotel record missing for this order",
                'code' => 500
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'order_id'       => $order->id,
                'hotel_id'       => $order->hotel->id,
                'status'         => $order->status,            // pending, paid, failed
                'amount'         => $order->amount,
                'transaction_id' => $order->mpesa_transaction_id,
                'paid_at'        => $order->paid_at,
            ]
        ]);

    } catch (\Exception $e) {

        // Log the exception
        \Log::error("Order status check error: " . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => "An unexpected error occurred",
            'error' => $e->getMessage(),
            'code' => 500,
        ], 500);
    }
}

}