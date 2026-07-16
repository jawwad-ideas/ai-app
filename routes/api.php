<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KnowledgeController;


Route::prefix('vector')->group(function () {

    Route::post('/collection', [KnowledgeController::class, 'createCollection']);

    Route::post('/store', [KnowledgeController::class, 'store']);

    Route::post('/search', [KnowledgeController::class, 'search']);

    Route::delete('/{id}', [KnowledgeController::class, 'delete']);
});
