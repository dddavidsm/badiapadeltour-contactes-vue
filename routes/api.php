<?php

use App\Http\Controllers\Api\ComplejoController;
use App\Http\Controllers\Api\GuestCheckoutController;
use App\Http\Controllers\Api\PistaController;
use Illuminate\Support\Facades\Route;

Route::apiResource('pistas', PistaController::class);
Route::apiResource('complejos', ComplejoController::class);
Route::post('checkout/availability', [GuestCheckoutController::class, 'checkAvailability']);
Route::post('checkout', [GuestCheckoutController::class, 'checkout']);
