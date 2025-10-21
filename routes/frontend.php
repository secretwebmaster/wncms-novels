<?php

use Illuminate\Support\Facades\Route;
use Secretwebmaster\WncmsNovels\Http\Controllers\Frontend\NovelController;
use Secretwebmaster\WncmsNovels\Http\Controllers\Frontend\ChapterController;

// Novels
Route::prefix('novel')->controller(NovelController::class)->group(function () {
    Route::get('/', 'index')->name('frontend.novels.index');
    Route::get('/{slug}', 'show')->name('frontend.novels.show');
});

// Chapters
Route::prefix('chapter')->controller(ChapterController::class)->group(function () {
    Route::get('/{slug}', 'show')->name('frontend.chapters.show');
});
