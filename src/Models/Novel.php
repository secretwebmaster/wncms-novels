<?php

namespace Secretwebmaster\WncmsNovels\Models;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Wncms\Interfaces\ApiModelInterface;
use Wncms\Models\BaseModel;
use Wncms\Traits\HasApi;

class Novel extends BaseModel implements HasMedia, ApiModelInterface
{
    use InteractsWithMedia;
    use HasApi;

    public static $packageId = 'wncms-novels';
    public static $modelKey = 'novel';

    protected $table = 'novels';
    protected $guarded = [];

    protected static array $tagMetas = [];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_recommended' => 'boolean',
        'is_dmca' => 'boolean',
        'price' => 'decimal:3',
        'published_at' => 'datetime',
        'expired_at' => 'datetime',
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

    const SERIES_STATUS_ONGOING = 0;
    const SERIES_STATUS_COMPLETED = 1;
    const SERIES_STATUS_PAUSED = 2;
    const SERIES_STATUS_DROPPED = 3;
    const SERIES_STATUS_UPCOMING = 4;

    public static array $seriesStatusLabels = [
        self::SERIES_STATUS_ONGOING => 'ongoing',
        self::SERIES_STATUS_COMPLETED => 'completed',
        self::SERIES_STATUS_PAUSED => 'paused',
        self::SERIES_STATUS_DROPPED => 'dropped',
        self::SERIES_STATUS_UPCOMING => 'upcoming',
    ];

    public function getSeriesStatusLabelAttribute(): string
    {
        $key = static::$seriesStatusLabels[$this->series_status] ?? 'not_available';
        return __('wncms-novels::word.' . $key);
    }

    public function chapters()
    {
        return $this->hasMany(NovelChapter::class, 'novel_id');
    }

    public function updateWordCount()
    {
        $wordCount = 0;
        foreach($this->chapters as $chapter){
            $wordCount += mb_strlen(trim($chapter->content));
        }

        $this->word_count = $wordCount;
        $this->save();
    }
}
