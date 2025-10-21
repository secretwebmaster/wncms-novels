<?php

namespace Secretwebmaster\WncmsNovels\Models;

use Wncms\Models\BaseModel;

class Novel extends BaseModel
{
    protected $table = 'novels';

    protected $guarded = [];

    protected $casts = [
        'is_pinned'       => 'boolean',
        'is_recommended'  => 'boolean',
        'is_dmca'         => 'boolean',
        'price'           => 'decimal:3',
        'published_at'    => 'datetime',
        'expired_at'      => 'datetime',
    ];

    public const ICONS = [
        'fontawesome' => 'fa-solid fa-book',
    ];

    public const ORDERS = [
        'id',
        'status',
        'order',
        'is_pinned',
        'is_recommended',
        'chapter_count',
        'created_at',
        'updated_at',
    ];

    public const ROUTES = [
        'index',
        'create',
    ];

    public const STATUSES = [
        'published',
        'drafted',
        'trashed',
    ];

    const SERIES_STATUS_ONGOING  = 0;
    const SERIES_STATUS_COMPLETED = 1;
    const SERIES_STATUS_PAUSED   = 2;
    const SERIES_STATUS_DROPPED  = 3;
    const SERIES_STATUS_UPCOMING = 4;

    public static array $seriesStatusLabels = [
        self::SERIES_STATUS_ONGOING  => 'ongoing',
        self::SERIES_STATUS_COMPLETED => 'completed',
        self::SERIES_STATUS_PAUSED   => 'paused',
        self::SERIES_STATUS_DROPPED  => 'dropped',
        self::SERIES_STATUS_UPCOMING => 'upcoming',
    ];

    /**
     * Accessors
     */
    public function getSeriesStatusLabelAttribute(): string
    {
        return __('wncms-novels.word.' . static::$seriesStatusLabels[$this->series_status] ?? 'not_available');
    }

    /**
     * Relationships
     */
    public function chapters()
    {
        return $this->hasMany(\Secretwebmaster\WncmsNovels\Models\NovelChapter::class, 'novel_id');
    }
}
