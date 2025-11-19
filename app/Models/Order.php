<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'hotel_id', 'customer_phone', 'amount', 'menu_items',
        'mpesa_checkout_request_id', 'mpesa_transaction_id',
        'status', 'paid_at'
    ];

    protected $casts = [
        'menu_items' => 'array',
        'paid_at' => 'datetime',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}