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
            'mpesa_consumer_key' => 'VHPaIr2XBYq5iwzm2WIGAmdm4yGHEygmMkpC3ZUkMSA3lThY',
            'mpesa_consumer_secret' => 'xaLJFDMzIdNr7A6wrqfhxON3pMv0oidRgWZms5U9RWeWaC1oFtuHg406w1QTBFlp',
            'mpesa_shortcode' => '174379',
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
            'mpesa_consumer_secret' => 'LNYdODkNG8SeWGmQomCmmVcOJ5D7FUF1zswzEARhPIOJQQxXUZmAkx2GwpcdneJG',
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
            'mpesa_consumer_key' => 'W6NwYtOd00rhPpMgXb066OhZkgbVHFJnrpWA7dkaYGhSwE8A',
            'mpesa_consumer_secret' => 'lh5RzNIWC74DPh0XtF7oJp7x69j48ZTnXaZZYps8eGmXm1S9MN3N5BSq3GnWJ965',
           'mpesa_shortcode' => '174379',
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
            'mpesa_consumer_key' => 'tNkLnKXcANXILAAe1KxBVKGzJgw4uaOdGgMJpMrbldIYQGAm',
            'mpesa_consumer_secret' => '63fVCJqfrRXowyJfSeXPxw84heH0FgDICxWQIBFxuWIenIHQ219dvosfguHV2UMR',
            'mpesa_shortcode' => '174379',
            'mpesa_passkey' => 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919',
            'mpesa_environment' => 'sandbox',
        ]);
         Hotel::create([
            'name' => 'Hotel Four',
            'email' => 'hotelone@test.com',
            'phone' => '254712345678',
            'payment_integration_type' => 'direct',
            'subscription_plan' => 'standard',
            'subscription_fee' => 5000.00,
            'subscription_status' => 'active',
            'subscription_next_billing_date' => Carbon::now()->addMonth(),
            'mpesa_consumer_key' => '4Y6KnykyOEEyZEcWJrPGx2OxgO9a2m1CDB81Jz5FWneFhjEH',
            'mpesa_consumer_secret' => 'XAhE5m7vMRrAPylKGAviAdEEySwxXI1KLbZLIjWikt3CyTcq5Ay4XRpHIB4nZlqy',
            'mpesa_shortcode' => '174379',  // ✅ Everyone uses this fixed number
            'mpesa_passkey' => 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919',  // ✅ Everyone uses this fixed key
            'mpesa_environment' => 'sandbox',
        ]);
    }
}
