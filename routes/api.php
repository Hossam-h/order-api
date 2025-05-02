<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('orders')->group(function () {
    Route::post('/create', [OrderController::class, 'store']);
    Route::get('/', [OrderController::class, 'index']);
    Route::put('/{id}', [OrderController::class, 'update']);
    Route::get('/stats', [OrderController::class, 'stats']);
}); 