<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'hotel_id' => 'required|exists:hotels,id',
                'customer_phone' => 'required|string',
                'amount' => 'required|numeric|min:1',
                'menu_items' => 'nullable|array'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        try {
            $hotel = Hotel::findOrFail($validated['hotel_id']);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hotel not found'
            ], 404);
        }

        // Check if hotel subscription is active
        if (!$hotel->is_active || $hotel->subscription_status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Hotel subscription is not active'
            ], 403);
        }

        try {
            $order = Order::create([
                'hotel_id' => $validated['hotel_id'],
                'customer_phone' => $validated['customer_phone'],
                'amount' => $validated['amount'],
                'menu_items' => $validated['menu_items'] ?? [],
                'status' => 'pending'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order created successfully',
            'data' => $order
        ], 201);
    }

    public function show($id)
    {
        try {
            $order = Order::with('hotel')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }
}
