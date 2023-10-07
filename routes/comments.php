<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Comment\{
    StoreCommentController,
    IndexCommentController,
    ShowCommentController,
    UpdateCommentController,
    DeleteCommentController,
};

// Rotas Públicas
Route::prefix('comments')->group(function () {
    Route::get('/', IndexCommentController::class);
    Route::get('/{id}', ShowCommentController::class);
});

// Rotas Privadas
Route::prefix('comments')->middleware('auth:sanctum')->group(function () {
    Route::post('/', StoreCommentController::class);
    Route::patch('/{id}', UpdateCommentController::class);
    Route::delete('/{id}', DeleteCommentController::class);
});
