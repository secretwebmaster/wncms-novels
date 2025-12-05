<?php

use Illuminate\Support\Facades\Route;
use Secretwebmaster\WncmsNovels\Http\Controllers\Frontend\NovelController;
use Secretwebmaster\WncmsNovels\Http\Controllers\Frontend\NovelChapterController;

// Novels
Route::prefix('novel')->controller(NovelController::class)->group(function () {
    Route::get('/search', 'search')->name('frontend.novels.search');
    Route::get('/', 'index')->name('frontend.novels.index');
    Route::get('/{type}/{slug}', [NovelController::class, 'tag'])->where('type', wncms()->tag()->getTagTypesForRoute(wncms()->getModelClass('novel')))->name('frontend.novels.tag');
    Route::get('/{slug}', 'show')->name('frontend.novels.show');
    Route::get('search/{keyword}', 'result')->name('frontend.novels.search.result');
    Route::post('search', 'search')->name('frontend.novels.search');
});

// Chapters
Route::prefix('novel/{novelSlug}chapter')->controller(NovelChapterController::class)->group(function () {
    Route::get('/{chapterSlug}', 'show')->name('frontend.novels.chapters.show');
});
