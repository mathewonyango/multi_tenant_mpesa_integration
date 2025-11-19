<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\HotelManagementController;


// Orders
Route::prefix('orders')->group(function () {
    Route::post('/', [OrderController::class, 'create']);
    Route::get('/{id}', [OrderController::class, 'show']);
});

// Payments
Route::prefix('payments')->group(function () {
    Route::post('/initiate', [PaymentController::class, 'initiatePayment']);
    Route::get('/status/{orderId}', [PaymentController::class, 'checkStatus']);
});

Route::post('/subscription/pay', [PaymentController::class, 'initiateSubscriptionPayment']);

// M-PESA Callback
Route::post('/mpesa/callback', [PaymentController::class, 'callback']);



//Management Routes

// Route::prefix('management')->name('management.')->group(function () {
//     // Dashboard
//     Route::get('/', [HotelManagementController::class, 'index'])->name('dashboard');
    
//     // Hotel Onboarding
//     Route::get('/hotels/create', [HotelManagementController::class, 'create'])->name('onboarding');
//     Route::post('/hotels', [HotelManagementController::class, 'store'])->name('store');
    
//     // Hotel Management
//     Route::get('/hotels/{hotel}', [HotelManagementController::class, 'show'])->name('show');
//     Route::get('/hotels/{hotel}/edit', [HotelManagementController::class, 'edit'])->name('edit');
//     Route::put('/hotels/{hotel}', [HotelManagementController::class, 'update'])->name('update');
//     Route::delete('/hotels/{hotel}', [HotelManagementController::class, 'destroy'])->name('destroy');
// });