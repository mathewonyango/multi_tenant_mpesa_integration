<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hotel_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->date('period_start');
            $table->date('period_end');
            $table->integer('total_orders_count');
            $table->decimal('total_orders_amount', 10, 2);
            $table->decimal('payout_amount', 10, 2);
            $table->enum('payout_status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->enum('payout_method', ['bank_transfer', 'mpesa_b2c'])->nullable();
            $table->string('payout_reference')->nullable();
            $table->timestamp('payout_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hotel_payouts');
    }
};