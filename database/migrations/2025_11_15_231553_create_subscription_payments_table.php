<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('subscription_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('billing_month', 7); // Format: 2025-11
            $table->enum('payment_status', ['pending', 'paid', 'overdue', 'failed'])->default('pending');
            $table->enum('payment_method', ['mpesa', 'bank', 'cash'])->nullable();
            $table->string('mpesa_transaction_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscription_payments');
    }
};