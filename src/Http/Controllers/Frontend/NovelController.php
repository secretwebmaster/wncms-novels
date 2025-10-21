<?php

namespace Secretwebmaster\WncmsNovels\Http\Controllers\Frontend;

use Wncms\Http\Controllers\Frontend\FrontendController;
use Illuminate\View\View;

class NovelController extends FrontendController
{
    /**
     * Display a single novel with its chapters.
     */
    public function show(string $slug): View
    {
        $manager = wncms()
            ->package('wncms-novels')
            ->novel();

        $novel = $manager->get([
            'slug'   => $slug,
            'withs'  => ['chapters'],
            'cache'  => true,
        ]);

        if (!$novel) {
            abort(404);
        }

        return view("frontend.theme.{$this->theme}.novels.show", compact('novel'));
    }

    /**
     * Display a list of novels.
     */
    public function index(): View
    {
        $manager = wncms()
            ->package('wncms-novels')
            ->novel();

        $novels = $manager->getList([
            'withs'     => ['chapters'],
            'order'     => 'id',       // Correct usage
            'sequence'  => 'desc',     // Correct usage
            'page_size' => 20,         // For pagination
            'cache'     => true,
        ]);

        return view("frontend.theme.{$this->theme}.novels.index", compact('novels'));
    }
}
