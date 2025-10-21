<?php

namespace Secretwebmaster\WncmsNovels\Services\Managers;

use Wncms\Services\Managers\ModelManager;
use Secretwebmaster\WncmsNovels\Models\Novel;
use Illuminate\Database\Eloquent\Builder;

class NovelManager extends ModelManager
{
    protected string|array $cacheTags = ['novels'];

    /**
     * Return the handled model class.
     */
    public function getModelClass(): string
    {
        return Novel::class;
    }

    /**
     * Build the base query for listing novels.
     */
    protected function buildListQuery(array $options): Builder
    {
        $q = $this->query();

        // --- Optional filters ---
        $this->applyWebsiteId($q, $options['website_id'] ?? null);

        if (!empty($options['keywords'])) {
            $this->applyKeywordFilter($q, $options['keywords'], ['title', 'description', 'author']);
        }

        if (!empty($options['status'])) {
            $this->applyStatus($q, 'status', $options['status']);
        }

        // --- Relations ---
        if (!empty($options['withs'])) {
            $this->applyWiths($q, $options['withs']);
        }

        // --- Ordering ---
        $this->applyOrdering(
            $q,
            $options['order'] ?? 'id',
            $options['sequence'] ?? 'desc',
            $options['is_random'] ?? false
        );

        // --- Pagination / limit / offset ---
        $this->applyOffset($q, $options['offset'] ?? 0);
        $this->applyLimit($q, $options['count'] ?? 0);

        return $q;
    }
}
