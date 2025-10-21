<?php

use Illuminate\Support\Facades\Route;
use Secretwebmaster\WncmsNovels\Http\Controllers\Backend\NovelController;
use Secretwebmaster\WncmsNovels\Http\Controllers\Backend\NovelChapterController;

Route::prefix('panel')
    ->middleware(['web', 'auth', 'is_installed', 'has_website'])
    ->group(function () {

        // Novels
        Route::prefix('novels')->controller(NovelController::class)->group(function () {
            Route::get('/', 'index')->name('novels.index')->middleware('can:novel_index');
            Route::get('/create', 'create')->name('novels.create')->middleware('can:novel_create');
            Route::get('/create/{id}', 'create')->name('novels.clone')->middleware('can:novel_create');
            Route::post('/', 'store')->name('novels.store')->middleware('can:novel_create');
            Route::get('/{id}/edit', 'edit')->name('novels.edit')->middleware('can:novel_edit');
            Route::patch('/{id}', 'update')->name('novels.update')->middleware('can:novel_edit');
            Route::delete('/{id}', 'destroy')->name('novels.destroy')->middleware('can:novel_delete');
        });

        // Novel Chapters
        Route::prefix('novel-chapters')->controller(NovelChapterController::class)->group(function () {
            Route::get('/', 'index')->name('novel_chapters.index')->middleware('can:novel_chapter_index');
            Route::get('/create', 'create')->name('novel_chapters.create')->middleware('can:novel_chapter_create');
            Route::get('/create/{id}', 'create')->name('novel_chapters.clone')->middleware('can:novel_chapter_create');
            Route::post('/', 'store')->name('novel_chapters.store')->middleware('can:novel_chapter_create');
            Route::get('/{id}/edit', 'edit')->name('novel_chapters.edit')->middleware('can:novel_chapter_edit');
            Route::patch('/{id}', 'update')->name('novel_chapters.update')->middleware('can:novel_chapter_edit');
            Route::delete('/{id}', 'destroy')->name('novel_chapters.destroy')->middleware('can:novel_chapter_delete');
        });
    });
