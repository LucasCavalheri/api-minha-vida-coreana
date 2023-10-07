<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Like\{
    StoreLikeController,
    DeleteLikeController,
};

Route::prefix('likes')->middleware('auth:sanctum')->group(function () {
    Route::post('/', StoreLikeController::class);
    Route::delete('/{id}', DeleteLikeController::class);
});
