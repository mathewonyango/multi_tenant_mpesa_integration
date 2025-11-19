@extends('layouts.app')

@section('title', 'Hotel Management Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Header -->
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Hotel Management Dashboard</h1>
            <p class="text-gray-600 mt-2">Manage hotels, payments, and monitor system performance</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            @include('Management.partials.stats-card', [
                'title' => 'Total Hotels',
                'value' => $totalHotels,
                'description' => $activeHotels . ' active hotels',
                'icon' => 'building-2',
                'trend' => '+12%'
            ])

            @include('Management.partials.stats-card', [
                'title' => 'Total Orders',
                'value' => number_format($totalOrders),
                'description' => 'Across all hotels',
                'icon' => 'shopping-cart',
                'trend' => '+8%'
            ])

            @include('Management.partials.stats-card', [
                'title' => 'Total Revenue',
                'value' => 'KES ' . number_format($totalRevenue, 2),
                'description' => 'All time',
                'icon' => 'dollar-sign',
                'trend' => '+15%'
            ])

            @include('Management.partials.stats-card', [
                'title' => 'Aggregated Revenue',
                'value' => 'KES ' . number_format($aggregatedRevenue, 2),
                'description' => 'Pending payout to hotels',
                'icon' => 'trending-up',
                'trend' => null
            ])
        </div>

        <!-- Tabs Navigation -->
        <div class="bg-white rounded-lg shadow">
           <div class="border-b border-gray-200">
    <nav class="flex space-x-8 px-6" aria-label="Tabs">
        <a href="{{ route('management.dashboard', ['tab' => 'all']) }}" 
           class="border-b-2 py-4 px-1 text-sm font-medium {{ $activeTab === 'all' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            All Hotels ({{ $totalHotels }})
        </a>
        <a href="{{ route('management.dashboard', ['tab' => 'direct']) }}" 
           class="border-b-2 py-4 px-1 text-sm font-medium {{ $activeTab === 'direct' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            Direct Payment ({{ $directPaymentCount }})
        </a>
        <a href="{{ route('management.dashboard', ['tab' => 'aggregated']) }}" 
           class="border-b-2 py-4 px-1 text-sm font-medium {{ $activeTab === 'aggregated' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            Aggregated ({{ $aggregatedPaymentCount }})
        </a>
        <!-- New Aggregator Balance Tab -->
        <a href="{{ route('management.dashboard', ['tab' => 'aggregator_balance']) }}" 
           class="border-b-2 py-4 px-1 text-sm font-medium {{ $activeTab === 'aggregator_balance' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            Aggregator Balance ({{ number_format($aggregatedRevenue, 2) }})
        </a>
    </nav>
</div>


            <div class="p-6">
                @if($activeTab === 'all')
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-semibold">All Hotels</h2>
                            <a href="{{ route('management.onboarding') }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add Hotel
                            </a>
                        </div>
                        @include('Management.partials.hotels-table', ['hotels' => $allHotels])
                    </div>
                @endif

                @if($activeTab === 'direct')
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <h2 class="text-xl font-semibold">Direct Payment Hotels</h2>
                            <p class="text-sm text-gray-600">
                                These hotels have their own M-Pesa integration. Payments go directly to their paybill.
                            </p>
                            <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-700">Total Revenue (Direct)</span>
                                    <span class="text-2xl font-bold text-blue-600">
                                        KES {{ number_format($directRevenue, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @include('Management.partials.hotels-table', ['hotels' => $directHotels])
                    </div>
                @endif

                @if($activeTab === 'aggregated')
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <h2 class="text-xl font-semibold">Aggregated Payment Hotels</h2>
                            <p class="text-sm text-gray-600">
                                System manages payments for these hotels. Review and process payouts below.
                            </p>
                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-700">Total Collected</span>
                                        <span class="text-2xl font-bold text-blue-600">
                                            KES {{ number_format($aggregatedRevenue, 2) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-700">Pending Payouts</span>
                                        <span class="text-2xl font-bold text-yellow-600">
                                            KES {{ number_format($aggregatedRevenue, 2) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @include('Management.partials.hotels-table', ['hotels' => $aggregatedHotels])
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Add any JavaScript interactions here
    console.log('Dashboard loaded');
</script>
@endpush
@endsection
