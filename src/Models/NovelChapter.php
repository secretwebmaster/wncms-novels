<?php

namespace Secretwebmaster\WncmsNovels\Models;

use Wncms\Models\BaseModel;

class NovelChapter extends BaseModel
{
    protected $table = 'novel_chapters';

    protected $guarded = [];

    protected $casts = [
        'price'        => 'decimal:3',
        'published_at' => 'datetime',
        'expired_at'   => 'datetime',
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
        return $this->belongsTo(\Secretwebmaster\WncmsNovels\Models\Novel::class, 'novel_id');
    }
}
