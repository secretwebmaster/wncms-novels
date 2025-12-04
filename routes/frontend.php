<?php

use Illuminate\Support\Facades\Route;
use Secretwebmaster\WncmsNovels\Http\Controllers\Frontend\NovelController;
use Secretwebmaster\WncmsNovels\Http\Controllers\Frontend\NovelChapterController;

// Novels
Route::prefix('novel')->controller(NovelController::class)->group(function () {
    Route::get('/', 'index')->name('frontend.novels.index');
    Route::get('/{slug}', 'show')->name('frontend.novels.show');
});

// Chapters
Route::prefix('novel/{novelSlug}chapter')->controller(NovelChapterController::class)->group(function () {
    Route::get('/{chapterSlug}', 'show')->name('frontend.novels.chapters.show');
});
