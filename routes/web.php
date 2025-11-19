<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HotelManagementController;


Route::get('/', function () {
    return view('welcome');
});

Route::prefix('management')->name('management.')->group(function () {
    Route::get('/', [HotelManagementController::class, 'index'])->name('dashboard');
    Route::get('/hotels/create', [HotelManagementController::class, 'create'])->name('onboarding');
    Route::post('/hotels', [HotelManagementController::class, 'store'])->name('store');
    Route::get('/hotels/{hotel}', [HotelManagementController::class, 'show'])->name('show');
    Route::get('/hotels/{hotel}/edit', [HotelManagementController::class, 'edit'])->name('edit');
    Route::put('/hotels/{hotel}', [HotelManagementController::class, 'update'])->name('update');
    Route::delete('/hotels/{hotel}', [HotelManagementController::class, 'destroy'])->name('destroy');
});
