<?php

use App\Http\Controllers\Api\GeocodeController;
use App\Http\Controllers\Api\PaymentCallbackController;
use App\Http\Controllers\Api\SeoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Midtrans Webhook
Route::post('/payments/midtrans-callback', [PaymentCallbackController::class, 'midtransCallback']);

// Geocoding Proxy Endpoint (Bebas CORS & Rate Limit)
Route::get('/geocode', [GeocodeController::class, 'search']);
Route::get('/geocode/reverse', [GeocodeController::class, 'reverse']);

// SEO Growth Engine API Endpoints
Route::middleware('seo.api.token')->prefix('seo')->group(function () {
    Route::get('/products/{id}/data', [SeoController::class, 'getProductData']);
    Route::post('/products/{id}/save-results', [SeoController::class, 'saveProductResults']);
    Route::post('/images/save-alts', [SeoController::class, 'saveImageAlts']);
});
