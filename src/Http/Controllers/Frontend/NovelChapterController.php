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

        $novelId = $chapter->novel_id;

        // Previous chapter
        $prevChapter = wncms()
            ->package('wncms-novels')
            ->novel_chapter()
            ->get([
                'wheres' => [
                    ['novel_id', '=', $novelId],
                    ['id', '<', $chapter->id],
                ],
                'order' => 'id',
                'sequence' => 'desc',
                'cache' => true,
            ]);

        // Next chapter
        $nextChapter = wncms()
            ->package('wncms-novels')
            ->novel_chapter()
            ->get([
                'wheres' => [
                    ['novel_id', '=', $novelId],
                    ['id', '>', $chapter->id],
                ],
                'order' => 'id',
                'sequence' => 'asc',
                'cache' => true,
            ]);
            
        return $this->view(
            $this->theme . "::chapters.show",
            [
                'pageTitle' => $chapter->title,
                'novel' => $chapter->novel,
                'chapter' => $chapter,
                'prevChapter' => $prevChapter,
                'nextChapter' => $nextChapter,
            ],
            "frontend.themes.{$this->theme}.chapters.show",
        );
    }
}
