<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Hotel;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'customer_phone' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'menu_items' => 'nullable|array'
        ]);
        
        $hotel = Hotel::findOrFail($validated['hotel_id']);
        
        // Check if hotel subscription is active
        if (!$hotel->is_active || $hotel->subscription_status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Hotel subscription is not active'
            ], 403);
        }
        
        $order = Order::create([
            'hotel_id' => $validated['hotel_id'],
            'customer_phone' => $validated['customer_phone'],
            'amount' => $validated['amount'],
            'menu_items' => $validated['menu_items'] ?? [],
            'status' => 'pending'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Order created successfully',
            'data' => $order
        ], 201);
    }
    
    public function show($id)
    {
        $order = Order::with('hotel')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }
}