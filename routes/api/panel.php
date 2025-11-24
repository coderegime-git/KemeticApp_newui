<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Panel\CartController;
use App\Http\Controllers\Api\Panel\PaymentsController;
use App\Http\Controllers\Api\Panel\ProductOrderController;
use App\Http\Controllers\Api\Panel\SessionsController;
use App\Http\Controllers\Api\Panel\SubscribesController;
use App\Http\Controllers\Api\Panel\RegistrationPackagesController;

Route::group(['middleware' => ['api', 'force.json']], function () {
    // Checkout routes
    Route::get('checkout/{user}', [CartController::class, 'webCheckoutRender'])->name('api.panel.checkout');
    Route::get('charge/{user}', [PaymentsController::class, 'webChargeRender'])->name('api.panel.charge');
    Route::get('subscribe/{user}/{subscribe}', [SubscribesController::class, 'webPayRender'])->name('api.panel.subscribe');
    Route::get('registration_packages/{user}/{package}', [RegistrationPackagesController::class, 'webPayRender'])->name('api.panel.registration_packages');

    // Session routes
    Route::group(['prefix' => 'sessions'], function () {
        Route::get('big_blue_button', [SessionsController::class, 'BigBlueButton'])->name('api.panel.big_blue_button');
        Route::get('agora', [SessionsController::class, 'agora'])->name('api.panel.agora');
    });
});
