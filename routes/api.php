<?php

use App\Http\Controllers\Api\MobileApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', [MobileApiController::class, 'register']);
Route::post('/auth/login', [MobileApiController::class, 'login']);

Route::get('/productos', [MobileApiController::class, 'productos']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [MobileApiController::class, 'me']);
    Route::post('/auth/logout', [MobileApiController::class, 'logout']);

    Route::get('/pedidos', [MobileApiController::class, 'pedidos']);
    Route::post('/pedidos', [MobileApiController::class, 'crearPedido']);
    Route::post('/stock-check', [MobileApiController::class, 'validarStock']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
