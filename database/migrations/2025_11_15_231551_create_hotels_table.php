<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->enum('payment_integration_type', ['direct', 'aggregated']);
            $table->boolean('is_active')->default(true);
            
            // Subscription
            $table->enum('subscription_plan', ['basic', 'standard', 'premium'])->default('basic');
            $table->decimal('subscription_fee', 10, 2);
            $table->enum('subscription_status', ['active', 'suspended', 'cancelled'])->default('active');
            $table->date('subscription_next_billing_date');
            $table->timestamp('last_payment_date')->nullable();
            
            // For Type A (Direct Integration)
            $table->string('mpesa_consumer_key')->nullable();
            $table->string('mpesa_consumer_secret')->nullable();
            $table->string('mpesa_shortcode')->nullable();
            $table->text('mpesa_passkey')->nullable();
            $table->enum('mpesa_environment', ['sandbox', 'production'])->default('sandbox');
            
            // For Type B (Aggregated)
            $table->string('payout_bank_account')->nullable();
            $table->string('payout_bank_name')->nullable();
            $table->enum('payout_schedule', ['daily', 'weekly'])->default('weekly');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hotels');
    }
};