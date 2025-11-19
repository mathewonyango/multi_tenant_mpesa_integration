<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hotel;
use Carbon\Carbon;

class HotelSeeder extends Seeder
{
    public function run()
    {
        // Type B (Aggregated) - Payment Aggregator
        Hotel::create([
            'name' => 'Payment Aggregator',
            'email' => 'aggregator@test.com',
            'phone' => '254745678901',
            'payment_integration_type' => 'aggregated',
            'subscription_plan' => 'standard',
            'subscription_fee' => 5000.00,
            'subscription_status' => 'active',
            'subscription_next_billing_date' => Carbon::now()->addMonth(),
            'mpesa_consumer_key' => 'VHPalr2XBYq5iwzm2WIGAmdm4yGHEygmMkpC3ZUkMSA3IThY',
            'mpesa_consumer_secret' => 'xaLJFDMzldNr7A6wrqfhxON3pMvOoidRgWZms5U9RWeWaC1oFtuHg406w1QTBFlp',
            'mpesa_shortcode' => '174379',  // ✅ Everyone uses this fixed number
            'mpesa_passkey' => 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919',  // ✅ Everyone uses this fixed key
            'mpesa_environment' => 'sandbox',
        ]);

        // Type B (Aggregated) - Hotel Three
        Hotel::create([
            'name' => 'Hotel Three',
            'email' => 'hotelthree@test.com',
            'phone' => '254734567890',
            'payment_integration_type' => 'aggregated',
            'subscription_plan' => 'basic',
            'subscription_fee' => 3000.00,
            'subscription_status' => 'active',
            'subscription_next_billing_date' => Carbon::now()->addMonth(),
            'mpesa_consumer_key' => 'wSNA3W2jw2D7sr4ca3TzX3fqZ9LhcBFxxU7qgYfwAfp7pEd8',
            'mpesa_consumer_secret' => 'LNYdODkNG8SeWGmQomCmmVcOJ5D7FUF1zswzEARhPlOJQqXxXUzmAkx2GwpcdneJG',
            'mpesa_shortcode' => '174379',  // ✅ Everyone uses this fixed number
            'mpesa_passkey' => 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919',  // ✅ Everyone uses this fixed key
            'mpesa_environment' => 'sandbox',
        ]);

        // Type A (Direct Integration) - Hotel Two
        Hotel::create([
            'name' => 'Hotel Two',
            'email' => 'hoteltwo@test.com',
            'phone' => '254723456789',
            'payment_integration_type' => 'direct',
            'subscription_plan' => 'premium',
            'subscription_fee' => 10000.00,
            'subscription_status' => 'active',
            'subscription_next_billing_date' => Carbon::now()->addMonth(),
            'mpesa_consumer_key' => 'W6N...',
            'mpesa_consumer_secret' => 'lh5R...',
           'mpesa_shortcode' => '174379',  // ✅ Everyone uses this fixed number
            'mpesa_passkey' => 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919',  // ✅ Everyone uses this fixed key
            'mpesa_environment' => 'sandbox',
        ]);

        // Type A (Direct Integration) - Hotel One
        Hotel::create([
            'name' => 'Hotel One',
            'email' => 'hotelone@test.com',
            'phone' => '254712345678',
            'payment_integration_type' => 'direct',
            'subscription_plan' => 'standard',
            'subscription_fee' => 5000.00,
            'subscription_status' => 'active',
            'subscription_next_billing_date' => Carbon::now()->addMonth(),
            'mpesa_consumer_key' => 'tNkLnKXcANXILAAe1KxBVKGzJgw4uaOdGgMJpMrbdlYQGAm',
            'mpesa_consumer_secret' => '63fVCJqfrRXowyJfSeXPxw84heH0FgDlCxWQIBFxuWIenIHQ219dvosfguHV2UMR',
            'mpesa_shortcode' => '174379',  // ✅ Everyone uses this fixed number
            'mpesa_passkey' => 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919',  // ✅ Everyone uses this fixed key
            'mpesa_environment' => 'sandbox',
        ]);
    }
}
