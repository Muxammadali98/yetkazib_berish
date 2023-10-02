<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const PAGE_SIZE = 20;
    public const TELEGRAM_PAGE_SIZE = 10;
    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 0;

    public const CATEGORY = 1;
    public const SUB_CATEGORY = 2;

    public static function getStatus(): array
    {
        return [
            self::STATUS_ACTIVE => 'Faol',
            self::STATUS_INACTIVE => 'No faol'
        ];
    }

    public static function getStep(): array
    {
        return [
            self::CATEGORY => 'Kategoriya',
            self::SUB_CATEGORY => 'Sub-kategoriya'
        ];
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
