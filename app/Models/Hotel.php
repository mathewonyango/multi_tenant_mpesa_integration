<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'payment_integration_type', 'is_active',
        'subscription_plan', 'subscription_fee', 'subscription_status',
        'subscription_next_billing_date', 'last_payment_date',
        'mpesa_consumer_key', 'mpesa_consumer_secret', 'mpesa_shortcode',
        'mpesa_passkey', 'mpesa_environment',
        'payout_bank_account', 'payout_bank_name', 'payout_schedule'
    ];

    protected $casts = [
        'subscription_next_billing_date' => 'date',
        'last_payment_date' => 'datetime',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function subscriptionPayments()
    {
        return $this->hasMany(SubscriptionPayment::class);
    }

    public function payouts()
    {
        return $this->hasMany(HotelPayout::class);
    }
}