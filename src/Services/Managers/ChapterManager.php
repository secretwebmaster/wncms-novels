<?php

namespace Secretwebmaster\WncmsNovels\Services\Managers;

use Wncms\Services\Managers\ModelManager;
use Secretwebmaster\WncmsNovels\Models\NovelChapter;
use Illuminate\Database\Eloquent\Builder;

class ChapterManager extends ModelManager
{
    protected string|array $cacheTags = ['novel_chapters'];
    
    /**
     * Return the handled model class.
     */
    public function getModelClass(): string
    {
        return NovelChapter::class;
    }

    /**
     * Build the base query for listing novel chapters.
     */
    protected function buildListQuery(array $options): Builder
    {
        $q = $this->query();

        // --- Optional filters ---
        if (!empty($options['novel_id'])) {
            $this->applyIds($q, 'novel_id', $options['novel_id']);
        }

        if (!empty($options['keywords'])) {
            $this->applyKeywordFilter($q, $options['keywords'], ['title', 'content']);
        }

        if (!empty($options['withs'])) {
            $this->applyWiths($q, $options['withs']);
        }

        // --- Ordering ---
        $this->applyOrdering(
            $q,
            $options['order'] ?? 'number',
            $options['sequence'] ?? 'asc',
            $options['is_random'] ?? false
        );

        // --- Pagination / limit / offset ---
        $this->applyOffset($q, $options['offset'] ?? 0);
        $this->applyLimit($q, $options['count'] ?? 0);

        return $q;
    }
}
