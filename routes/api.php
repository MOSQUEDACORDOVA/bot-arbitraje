<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BinanceController;

Route::get('/btc-price', [BinanceController::class, 'getBTCPrice']);

// Obtener saldo de una moneda específica o todas
Route::get('/balance', [BinanceController::class, 'getBalance']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
