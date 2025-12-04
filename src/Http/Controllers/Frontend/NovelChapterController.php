<?php

namespace Secretwebmaster\WncmsNovels\Http\Controllers\Frontend;

use Wncms\Http\Controllers\Frontend\FrontendController;
use Illuminate\View\View;

class NovelChapterController extends FrontendController
{
    /**
     * Display a single chapter and its parent novel.
     */
    public function show(string $novelSlug, string $slug): View
    {
        $manager = wncms()
            ->package('wncms-novels')
            ->novel_chapter();

        $chapter = $manager->get([
            'slug'  => $slug,
            'withs' => ['novel'],
            'cache' => true,
        ]);

        if (!$chapter) {
            abort(404);
        }

        // Load all chapters for navigation
        $novelId = $chapter['novel_id'] ?? $chapter->novel_id ?? null;

        $allChapters = [];
        if ($novelId) {
            $allChapters = wncms()
                ->package('wncms-novels')
                ->novel_chapter()
                ->getList([
                    'wheres'   => [['novel_id', '=', $novelId]],
                    'order'    => 'id',
                    'sequence' => 'asc',
                    'cache'    => true,
                ]);
        }

        // return view("frontend.themes.{$this->theme}.chapters.show", compact('chapter', 'allChapters'));
        return $this->view(
            $this->theme . "::chapters.show",
            [
                'pageTitle' => $chapter->title,
                'novel' => $chapter->novel,
                'chapter' => $chapter,
                'allChapters' => $allChapters,
            ],
            "frontend.themes.{$this->theme}.chapters.show",
        );
    }
}
