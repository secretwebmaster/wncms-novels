<?php

namespace Secretwebmaster\WncmsNovels\Http\Controllers\Frontend;

use Wncms\Http\Controllers\Frontend\FrontendController;
use Illuminate\View\View;

class ChapterController extends FrontendController
{
    /**
     * Display a single chapter and its parent novel.
     */
    public function show(string $slug): View
    {
        $manager = wncms()
            ->package('wncms-novels')
            ->chapter();

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
                ->chapter()
                ->getList([
                    'wheres'   => [['novel_id', '=', $novelId]],
                    'order'    => 'id',
                    'sequence' => 'asc',
                    'cache'    => true,
                ]);
        }

        return view("frontend.themes.{$this->theme}.chapters.show", compact('chapter', 'allChapters'));
    }
}
