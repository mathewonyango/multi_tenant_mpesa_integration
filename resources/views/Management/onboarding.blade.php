@extends('layouts.app')

@section('title', 'Hotel Onboarding')
@section('page-title', 'Hotel Onboarding')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Back Button -->
    <div class="flex items-center gap-4">
        <a href="{{ route('management.dashboard') }}" 
           class="inline-flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-white rounded-lg shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Dashboard
        </a>
    </div>

    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h1 class="text-2xl font-bold text-gray-900">Add New Hotel</h1>
        <p class="text-gray-600 mt-1">Fill in the hotel details to onboard them to the system</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span class="text-green-800 font-medium">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <!-- Error Messages -->
    @if($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
        <div class="flex">
            <svg class="w-5 h-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <div>
                <h3 class="text-red-800 font-semibold mb-2">Please fix the following errors:</h3>
                <ul class="list-disc list-inside text-red-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Onboarding Form -->
    <div class="bg-white rounded-lg shadow-sm">
        <form action="{{ route('management.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Basic Information Section -->
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Basic Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Hotel Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Safari Paradise Hotel"
                               required>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="info@hotel.co.ke"
                               required>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="+254712345678"
                               required>
                    </div>

                    <div>
                        <label for="subscription_plan" class="block text-sm font-medium text-gray-700 mb-1">
                            Subscription Plan <span class="text-red-500">*</span>
                        </label>
                        <select id="subscription_plan" 
                                name="subscription_plan" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                            <option value="">Select plan</option>
                            <option value="basic" {{ old('subscription_plan') === 'basic' ? 'selected' : '' }}>Basic - KES 2,000/month</option>
                            <option value="standard" {{ old('subscription_plan') === 'standard' ? 'selected' : '' }}>Standard - KES 3,000/month</option>
                            <option value="premium" {{ old('subscription_plan') === 'premium' ? 'selected' : '' }}>Premium - KES 5,000/month</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Payment Integration Section -->
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Payment Integration</h2>
                <div>
                    <label for="payment_integration_type" class="block text-sm font-medium text-gray-700 mb-1">
                        Payment Integration Type <span class="text-red-500">*</span>
                    </label>
                    <select id="payment_integration_type" 
                            name="payment_integration_type" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="">Select payment type</option>
                        <option value="direct" {{ old('payment_integration_type') === 'direct' ? 'selected' : '' }}>
                            Direct - Hotel has their own M-Pesa keys
                        </option>
                        <option value="aggregated" {{ old('payment_integration_type') === 'aggregated' ? 'selected' : '' }}>
                            Aggregated - System manages payments
                        </option>
                    </select>
                    <p class="text-sm text-gray-500 mt-2" id="payment-description">
                        Select how payments will be processed for this hotel
                    </p>
                </div>
            </div>

            <!-- M-Pesa Integration Fields (for DIRECT) -->
            <div id="mpesa-fields" class="space-y-4" style="display: none;">
                <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">M-Pesa Integration Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="mpesa_consumer_key" class="block text-sm font-medium text-gray-700 mb-1">
                            M-Pesa Consumer Key <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="mpesa_consumer_key" 
                               name="mpesa_consumer_key" 
                               value="{{ old('mpesa_consumer_key') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Enter consumer key">
                    </div>

                    <div>
                        <label for="mpesa_consumer_secret" class="block text-sm font-medium text-gray-700 mb-1">
                            M-Pesa Consumer Secret <span class="text-red-500">*</span>
                        </label>
                        <input type="password" 
                               id="mpesa_consumer_secret" 
                               name="mpesa_consumer_secret" 
                               value="{{ old('mpesa_consumer_secret') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Enter consumer secret">
                    </div>

                    <div>
                        <label for="mpesa_shortcode" class="block text-sm font-medium text-gray-700 mb-1">
                            M-Pesa Shortcode <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="mpesa_shortcode" 
                               name="mpesa_shortcode" 
                               value="{{ old('mpesa_shortcode') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="174379">
                    </div>

                    <div>
                        <label for="mpesa_passkey" class="block text-sm font-medium text-gray-700 mb-1">
                            M-Pesa Passkey <span class="text-red-500">*</span>
                        </label>
                        <input type="password" 
                               id="mpesa_passkey" 
                               name="mpesa_passkey" 
                               value="{{ old('mpesa_passkey') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Enter passkey">
                    </div>

                    <div class="md:col-span-2">
                        <label for="mpesa_environment" class="block text-sm font-medium text-gray-700 mb-1">
                            Environment <span class="text-red-500">*</span>
                        </label>
                        <select id="mpesa_environment" 
                                name="mpesa_environment" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="sandbox" {{ old('mpesa_environment') === 'sandbox' ? 'selected' : '' }}>Sandbox (Testing)</option>
                            <option value="production" {{ old('mpesa_environment') === 'production' ? 'selected' : '' }}>Production (Live)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Payout Details -->
            <div id="payout-fields" class="space-y-4" style="display: none;">
                <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Payout Details</h3>
                <p class="text-sm text-gray-600 mb-4" id="payout-description">
                    Bank details for receiving payouts
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="payout_bank_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Bank Name
                        </label>
                        <input type="text" 
                               id="payout_bank_name" 
                               name="payout_bank_name" 
                               value="{{ old('payout_bank_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Equity Bank">
                    </div>

                    <div>
                        <label for="payout_bank_account" class="block text-sm font-medium text-gray-700 mb-1">
                            Bank Account Number
                        </label>
                        <input type="text" 
                               id="payout_bank_account" 
                               name="payout_bank_account" 
                               value="{{ old('payout_bank_account') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="1234567890">
                    </div>

                    <div id="payout-schedule-field" style="display: none;">
                        <label for="payout_schedule" class="block text-sm font-medium text-gray-700 mb-1">
                            Payout Schedule <span class="text-red-500">*</span>
                        </label>
                        <select id="payout_schedule" 
                                name="payout_schedule" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select schedule</option>
                            <option value="weekly" {{ old('payout_schedule') === 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="bi-weekly" {{ old('payout_schedule') === 'bi-weekly' ? 'selected' : '' }}>Bi-weekly</option>
                            <option value="monthly" {{ old('payout_schedule') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('management.dashboard') }}" 
                   class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-sm">
                    Onboard Hotel
                </button>
            </div>
        </form>
    </div>
</div>

</div>

<script>
    // Place script directly in the page for immediate execution
    (function() {
        function togglePaymentFields(paymentType) {
            const mpesaFields = document.getElementById('mpesa-fields');
            const payoutFields = document.getElementById('payout-fields');
            const payoutScheduleField = document.getElementById('payout-schedule-field');
            const paymentDescription = document.getElementById('payment-description');
            const payoutDescription = document.getElementById('payout-description');

            console.log('Payment type selected:', paymentType); // Debug log

            // Reset all fields first
            mpesaFields.style.display = 'none';
            payoutFields.style.display = 'none';
            payoutScheduleField.style.display = 'none';

            if (paymentType === 'direct') {
                // Show M-Pesa fields and payout fields
                mpesaFields.style.display = 'block';
                payoutFields.style.display = 'block';
                payoutScheduleField.style.display = 'none';
                
                paymentDescription.innerHTML = '<strong>Direct Payment:</strong> Payments go directly to the hotel\'s M-Pesa paybill. Hotel manages their own M-Pesa integration.';
                payoutDescription.textContent = 'Optional: Bank details for receiving payouts from the system for other services';
                
                // Make M-Pesa fields required
                document.getElementById('mpesa_consumer_key').required = true;
                document.getElementById('mpesa_consumer_secret').required = true;
                document.getElementById('mpesa_shortcode').required = true;
                document.getElementById('mpesa_passkey').required = true;
                document.getElementById('mpesa_environment').required = true;
                
            } else if (paymentType === 'AGGREGATED') {
                // Show only payout fields with schedule
                mpesaFields.style.display = 'none';
                payoutFields.style.display = 'block';
                payoutScheduleField.style.display = 'block';
                
                paymentDescription.innerHTML = '<strong>Aggregated Payment:</strong> System collects all payments on behalf of the hotel and pays out according to schedule.';
                payoutDescription.textContent = 'Required: Bank details for receiving aggregated payouts from the system';
                
                // Make M-Pesa fields not required
                document.getElementById('mpesa_consumer_key').required = false;
                document.getElementById('mpesa_consumer_secret').required = false;
                document.getElementById('mpesa_shortcode').required = false;
                document.getElementById('mpesa_passkey').required = false;
                document.getElementById('mpesa_environment').required = false;
                
                // Make payout schedule required
                document.getElementById('payout_schedule').required = true;
                
            } else {
                paymentDescription.textContent = 'Select how payments will be processed for this hotel';
                
                // Make all fields not required
                document.getElementById('mpesa_consumer_key').required = false;
                document.getElementById('mpesa_consumer_secret').required = false;
                document.getElementById('mpesa_shortcode').required = false;
                document.getElementById('mpesa_passkey').required = false;
                document.getElementById('mpesa_environment').required = false;
                document.getElementById('payout_schedule').required = false;
            }
        }

        // Initialize immediately when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }

        function init() {
            const paymentTypeSelect = document.getElementById('payment_integration_type');
            
            if (!paymentTypeSelect) {
                console.error('Payment type select not found!');
                return;
            }
            
            // Add change event listener
            paymentTypeSelect.addEventListener('change', function() {
                console.log('Change event triggered:', this.value);
                togglePaymentFields(this.value);
            });
            
            // Initialize with current value (for when form has errors and old values)
            const currentValue = paymentTypeSelect.value;
            if (currentValue) {
                console.log('Initializing with value:', currentValue);
                togglePaymentFields(currentValue);
            }
        }
    })();
</script>
@endsection