<?php

// Rotas Públicas

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Post\{
    StorePostController,
    IndexPostController,
    ShowPostController,
    UpdatePostController,
    DeletePostController,
};

Route::prefix('posts')->group(function () {
    Route::get('/', IndexPostController::class);
    Route::get('/{id}', ShowPostController::class);
});

// Rotas Privadas
Route::prefix('posts')->middleware('auth:sanctum')->group(function () {
    Route::post('/', StorePostController::class);
    Route::patch('/{id}', UpdatePostController::class);
    Route::delete('/{id}', DeletePostController::class);
});
