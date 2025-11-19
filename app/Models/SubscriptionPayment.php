<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPayment extends Model
{
    // Fields allowed for mass assignment
    protected $fillable = [
        'hotel_id',
        'amount',
        'billing_month',
        'payment_status',
        'mpesa_checkout_request_id',
        'mpesa_transaction_id',
        'customer_phone',
        'paid_at'
    ];

    // Relationship: Subscription belongs to a Hotel
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
