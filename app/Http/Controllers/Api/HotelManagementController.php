<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; // ← Import the base Controller
use App\Models\Hotel;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HotelManagementController extends Controller
{
    /**
     * Display the management dashboard
     */
    public function index(Request $request)
{
    $activeTab = $request->get('tab', 'all');

    $allHotels = Hotel::withCount('orders')
        ->withSum('orders as total_revenue', 'amount')
        ->where('name', '!=', 'Payment Aggregator')
        ->orderBy('created_at', 'desc')
        ->get();

    $totalHotels = $allHotels->count();
    $activeHotels = $allHotels->where('is_active', true)->count();
    $totalOrders = Order::count();
    $totalRevenue = Order::where('status', 'completed')->sum('amount');

    $directHotels = $allHotels->where('payment_integration_type', 'direct');
    $aggregatedHotels = $allHotels->where('payment_integration_type', 'aggregated');
    //Aggregator balance totals
    // $subscriptionFee = 

    $directPaymentCount = $directHotels->count();
    $aggregatedPaymentCount = $aggregatedHotels->count();

    $directRevenue = Order::whereIn('hotel_id', $directHotels->pluck('id'))
        ->where('status', 'completed')
        ->sum('amount');

    $aggregatedRevenue = Order::whereIn('hotel_id', $aggregatedHotels->pluck('id'))
        ->where('status', 'completed')
        ->sum('amount');

    // Optional: filter hotels for active tab
    switch ($activeTab) {
        case 'direct':
            $hotels = $directHotels;
            break;
        case 'aggregated':
            $hotels = $aggregatedHotels;
            break;
        case 'aggregator_balance':
            $hotels = $aggregatedHotels; // you can customize the logic here
            break;
        default:
            $hotels = $allHotels;
            break;
    }

    return view('Management.dashboard', compact(
        'activeTab',
        'allHotels',
        'directHotels',
        'aggregatedHotels',
        'hotels',
        'totalHotels',
        'activeHotels',
        'totalOrders',
        'totalRevenue',
        'directPaymentCount',
        'aggregatedPaymentCount',
        'directRevenue',
        'aggregatedRevenue'
    ));
}


    public function create()
    {
        return view('Management.onboarding');
    }

    public function store(Request $request)
    {

        // dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:hotels,email',
            'phone' => 'required|string|max:20',
            'payment_integration_type' => 'required|in:DIRECT,AGGREGATED',
            'subscription_plan' => 'required|in:Basic,Standard,Premium',
            'mpesa_consumer_key' => 'nullable|string',
            'mpesa_consumer_secret' => 'nullable|string',
            'mpesa_shortcode' => 'nullable|string',
            'mpesa_passkey' => 'nullable|string',
            'mpesa_environment' => 'nullable|in:sandbox,production',
            'payout_bank_account' => 'nullable|string',
            'payout_bank_name' => 'nullable|string',
            'payout_schedule' => 'nullable|in:weekly,bi-weekly,monthly',
        ]);

        $subscriptionFees = [
            'Basic' => 2000,
            'Standard' => 3000,
            'Premium' => 5000,
        ];

        $validated['subscription_fee'] = $subscriptionFees[$validated['subscription_plan']];
        $validated['is_active'] = true;
        $validated['subscription_status'] = 'active';
        $validated['subscription_next_billing_date'] = now()->addMonth();

        $hotel = Hotel::create($validated);

        return redirect()
            ->route('management.dashboard')
            ->with('success', 'Hotel "' . $hotel->name . '" has been successfully onboarded!');
    }

    public function show(Hotel $hotel)
    {
        $hotel->load(['orders' => function ($query) {
            $query->orderBy('created_at', 'desc')->limit(50);
        }]);

        return view('Management.show', compact('hotel'));
    }

    public function edit(Hotel $hotel)
    {
        return view('Management.edit', compact('hotel'));
    }

    public function update(Request $request, Hotel $hotel)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:hotels,email,' . $hotel->id,
            'phone' => 'required|string|max:20',
            'payment_integration_type' => 'required|in:DIRECT,AGGREGATED',
            'subscription_plan' => 'required|in:Basic,Standard,Premium',
            'is_active' => 'boolean',
            'mpesa_consumer_key' => 'nullable|string',
            'mpesa_consumer_secret' => 'nullable|string',
            'mpesa_shortcode' => 'nullable|string',
            'mpesa_passkey' => 'nullable|string',
            'mpesa_environment' => 'nullable|in:sandbox,production',
            'payout_bank_account' => 'nullable|string',
            'payout_bank_name' => 'nullable|string',
            'payout_schedule' => 'nullable|in:weekly,bi-weekly,monthly',
        ]);

        $hotel->update($validated);

        return redirect()
            ->route('management.dashboard')
            ->with('success', 'Hotel "' . $hotel->name . '" has been updated!');
    }

    public function destroy(Hotel $hotel)
    {
        $hotel->is_active = false;
        $hotel->save();

        return redirect()
            ->route('management.dashboard')
            ->with('success', 'Hotel "' . $hotel->name . '" has been deactivated!');
    }
}
