<?php

namespace Secretwebmaster\WncmsNovels\Models;

use Spatie\MediaLibrary\HasMedia;
use Wncms\Models\BaseModel;
use Spatie\MediaLibrary\InteractsWithMedia;
use Wncms\Interfaces\ApiModelInterface;
use Wncms\Traits\HasApi;

class NovelChapter extends BaseModel implements HasMedia, ApiModelInterface
{
    use InteractsWithMedia;
    use HasApi;

    public static $packageId = 'wncms-novels';
    public static $modelKey = 'novel_chapter';

    protected $table = 'novel_chapters';

    protected $guarded = [];

    protected $casts = [
        'price'        => 'decimal:3',
        'published_at' => 'datetime',
        'expired_at'   => 'datetime',
    ];

    protected static array $apiRoutes = [
        [
            'name' => 'api.v1.novel_chapters.index',
            'key' => 'wncms_api_novel_chapter_index',
            'action' => 'index',
        ],
        [
            'name' => 'api.v1.novel_chapters.show',
            'key' => 'wncms_api_novel_chapter_show',
            'action' => 'show',
        ],
        [
            'name' => 'api.v1.novel_chapters.store',
            'key' => 'wncms_api_novel_chapter_store',
            'action' => 'store',
        ],
        [
            'name' => 'api.v1.novel_chapters.update',
            'key' => 'wncms_api_novel_chapter_update',
            'action' => 'update',
        ],
        [
            'name' => 'api.v1.novel_chapters.delete',
            'key' => 'wncms_api_novel_chapter_delete',
            'action' => 'delete',
        ],
    ];

    public const ICONS = [
        'fontawesome' => 'fa-solid fa-list',
    ];

    public const ORDERS = [
        'id',
        'status',
        'order',
        'number',
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

    /**
     * Relationships
     */
    public function novel()
    {
        return $this->belongsTo(Novel::class, 'novel_id');
    }
}
