<?php

namespace Secretwebmaster\WncmsNovels\Providers;

use Illuminate\Support\ServiceProvider;
use Secretwebmaster\WncmsNovels\Models\Novel;
use Secretwebmaster\WncmsNovels\Models\NovelChapter;
use Secretwebmaster\WncmsNovels\Services\Managers\NovelManager;
use Secretwebmaster\WncmsNovels\Services\Managers\ChapterManager;
use Secretwebmaster\WncmsNovels\Http\Controllers\Backend\NovelController;
use Secretwebmaster\WncmsNovels\Http\Controllers\Backend\NovelChapterController;
use Wncms\Facades\MacroableModels;

class WncmsNovelsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register singleton managers (frontend cache usage)
        $this->app->singleton('wncms.novel', fn() => new NovelManager());
        $this->app->singleton('wncms.novel_chapter', fn() => new ChapterManager());
    }

    public function boot(): void
    {
        // Load package resources
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'wncms-novels');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'wncms-novels');

        if ($this->app->runningInConsole()) {
            $this->commands([
                \Secretwebmaster\WncmsNovels\Console\Commands\GenerateDemoNovels::class,
            ]);
        }

        // Register package metadata with WNCMS
        wncms()->registerPackage('wncms-novels', [
            'base' => __DIR__ . '/../../',

            'controllers' => [
                'novel' => NovelController::class,
                'novel_chapter' => NovelChapterController::class,
            ],

            'info' => [
                'name' => [
                    'en'    => 'Novels',
                    'zh_TW' => '小說',
                    'zh_CN' => '小说',
                    'ja'    => '小説',
                ],
                'description' => [
                    'en'    => 'Manage novels and their chapters',
                    'zh_TW' => '管理小說與章節',
                    'zh_CN' => '管理小说与章节',
                    'ja'    => '小説と章を管理します',
                ],
                'version' => '1.0.0',
                'author'  => 'Secretwebmaster',
                'icon'    => 'fa-solid fa-book',
            ],

            'managers' => [
                'novel' => NovelManager::class,
                'novel_chapter' => ChapterManager::class,
            ],

            'menus' => [
                [
                    'title' => [
                        'en'    => 'Novels',
                        'zh_TW' => '小說',
                        'zh_CN' => '小说',
                        'ja'    => '小説',
                    ],
                    'icon'        => 'fa-solid fa-book',
                    'permission'  => 'novel_index',
                    'items' => [
                        [
                            'name'        => [
                                'en'    => 'Novel List',
                                'zh_TW' => '小說列表',
                                'zh_CN' => '小说列表',
                                'ja'    => '小説一覧',
                            ],
                            'route'       => 'novels.index',
                            'permission'  => 'novel_index',
                        ],
                        [
                            'name'        => [
                                'en'    => 'Create Novel',
                                'zh_TW' => '新增小說',
                                'zh_CN' => '新增小说',
                                'ja'    => '小説を作成',
                            ],
                            'route'       => 'novels.create',
                            'permission'  => 'novel_create',
                        ],
                    ],
                ],

                [
                    'title' => [
                        'en'    => 'Chapters',
                        'zh_TW' => '章節',
                        'zh_CN' => '章节',
                        'ja'    => '章',
                    ],
                    'icon'        => 'fa-solid fa-list',
                    'permission'  => 'novel_chapter_index',
                    'items' => [
                        [
                            'name'        => [
                                'en'    => 'Chapter List',
                                'zh_TW' => '章節列表',
                                'zh_CN' => '章节列表',
                                'ja'    => '章一覧',
                            ],
                            'route'       => 'novel_chapters.index',
                            'permission'  => 'novel_chapter_index',
                        ],
                        [
                            'name'        => [
                                'en'    => 'Create Chapter',
                                'zh_TW' => '新增章節',
                                'zh_CN' => '新增章节',
                                'ja'    => '章を作成',
                            ],
                            'route'       => 'novel_chapters.create',
                            'permission'  => 'novel_chapter_create',
                        ],
                    ],
                ],
            ],

            'models' => [
                'novel'   => Novel::class,
                'novel_chapter' => NovelChapter::class,
            ],

            'permissions' => [
                'novel_index',
                'novel_create',
                'novel_edit',
                'novel_delete',
                'novel_chapter_index',
                'novel_chapter_create',
                'novel_chapter_edit',
                'novel_chapter_delete',
            ],
        ]);

        // Load routes if they exist
        foreach (['web', 'api'] as $file) {
            $path = __DIR__ . "/../../routes/{$file}.php";
            if (file_exists($path)) {
                $this->loadRoutesFrom($path);
            }
        }

        $userModel    = wncms()->getModelClass('user');
        $novelModel   = wncms()->getModelClass('novel');
        $chapterModel = wncms()->getModelClass('novel_chapter');

        try {
            // User → Novels (user_id)
            MacroableModels::addMacro($userModel, 'novels', function () use ($novelModel) {
                return $this->hasMany($novelModel, 'user_id');
            });

            // Novel → User (user_id)
            MacroableModels::addMacro($novelModel, 'user', function () use ($userModel) {
                return $this->belongsTo($userModel, 'user_id');
            });

            // Novel → Chapters (novel_id)
            MacroableModels::addMacro($novelModel, 'chapters', function () use ($chapterModel) {
                return $this->hasMany($chapterModel, 'novel_id');
            });

            // Chapter → Novel (novel_id)
            MacroableModels::addMacro($chapterModel, 'novel', function () use ($novelModel) {
                return $this->belongsTo($novelModel, 'novel_id');
            });

            // Accessor: chapter_count
            MacroableModels::addMacro($novelModel, 'getChapterCountAttribute', function () {
                $this->loadMissing('chapters');
                return $this->chapters->count();
            });

            // Method: latestChapter()
            MacroableModels::addMacro($novelModel, 'latestChapter', function () {
                return $this->chapters()->orderBy('number', 'desc')->first();
            });
        } catch (\Throwable $e) {
            info('Novel macros not registered: ' . $e->getMessage());
        }
    }
}
