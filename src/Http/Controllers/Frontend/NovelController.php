<?php

namespace Secretwebmaster\WncmsNovels\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Wncms\Http\Controllers\Frontend\FrontendController;
use Illuminate\View\View;

class NovelController extends FrontendController
{
    public function show(string $slug): View
    {
        $manager = wncms()->package('wncms-novels')->novel();

        $novel = $manager->get([
            'slug'  => $slug,
            'withs' => [
                'tags',
                'tags.translations',
                'chapters' => function ($q) {
                    $q->orderBy('number', 'asc');
                },
            ],
            'cache' => true,
        ]);

        if (!$novel) {
            abort(404);
        }

        return $this->view(
            $this->theme . "::novels.show",
            [
                'pageTitle' => $novel->title,
                'novel' => $novel,
            ],
            "frontend.themes.{$this->theme}.novels.show"
        );
    }

    public function index(): View
    {
        $manager = wncms()->package('wncms-novels')->novel();

        $novels = $manager->getList([
            'withs'     => ['tags', 'tags.translations', 'chapters'],
            'order' => 'id',
            'sequence' => 'desc',
            'page_size' => 20,
            'cache' => true,
        ]);

        return $this->view(
            $this->theme . "::novels.index",
            [
                'novels' => $novels,
            ],
            "frontend.themes.{$this->theme}.novels.index"
        );
    }

    public function tag(string $type, string $slug): View
    {
        // 1. Get tag metas for novel model
        $novelClass = wncms()->getModelClass('novel');
        $metas = $novelClass::getTagMeta();

        // 2. Ensure short exists
        $meta = collect($metas)->firstWhere('short', $type);
        abort_unless($meta, 404);

        // actual full key, e.g. novel_category
        $fullKey = $meta['key'];

        // 3. Fetch actual tag model
        $tag = wncms()->tag()->get(['slug' => $slug, 'type' => $fullKey]);
        abort_unless($tag, 404);

        // 4. Fetch novels under this tag
        $manager = wncms()->package('wncms-novels')->novel();

        $novels = $manager->getList([
            'tags'     => [$tag->name],
            'tag_type' => $fullKey,
            'status'   => 'published',
            'page_size' => gto('novel_list_count', 12),
            'cache' => false,
        ]);

        return $this->view(
            $this->theme . "::novels.tag",
            [
                'tagSlug' => $slug,
                'novels' => $novels,
            ],
            "frontend.themes.{$this->theme}.novels.tag"
        );
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        return redirect()->route('frontend.novels.search.result', ['keyword' => $keyword]);
    }

    public function result(string $keyword): View
    {
        $manager = wncms()->package('wncms-novels')->novel();

        $novels = $manager->getList([
            'keywords'  => $keyword,
            'withs'     => ['tags', 'tags.translations', 'chapters'],
            'page_size' => 20,
            'cache'     => true,
        ]);

        return $this->view(
            $this->theme . "::novels.search",
            [
                'keyword' => $keyword,
                'novels' => $novels,
            ],
            "frontend.themes.{$this->theme}.novels.search"
        );
    }
}
