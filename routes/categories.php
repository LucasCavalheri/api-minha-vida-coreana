<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Category\{
    StoreCategoryController,
    IndexCategoryController,
};

Route::prefix('categories')->middleware('auth:sanctum')->group(function () {
    Route::post('/', StoreCategoryController::class);
    Route::get('/', IndexCategoryController::class);
});
