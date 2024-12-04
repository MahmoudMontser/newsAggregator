<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('articles')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\ArticleController::class, 'index']); // Fetch articles with filters
    Route::get('/sources', [\App\Http\Controllers\Api\ArticleController::class, 'getSources']); // Get list of sources
    Route::get('/categories', [\App\Http\Controllers\Api\ArticleController::class, 'getCategories']); // Get list of categories
});
