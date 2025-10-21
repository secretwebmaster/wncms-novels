<?php

use Illuminate\Support\Facades\Route;
use Secretwebmaster\WncmsNovels\Http\Controllers\Api\V1\NovelController;
use Secretwebmaster\WncmsNovels\Http\Controllers\Api\V1\NovelChapterController;

Route::prefix('api/v1')
    ->middleware(['api', 'is_installed', 'has_website'])
    ->group(function () {

        // Novels CRUD
        Route::apiResource('novels', NovelController::class)
            ->only(['index', 'show', 'store', 'update', 'destroy'])
            ->names('api.v1.novels');

        // Chapters CRUD
        Route::apiResource('chapters', NovelChapterController::class)
            ->only(['index', 'show', 'store', 'update', 'destroy'])
            ->names('api.v1.chapters');
    });
