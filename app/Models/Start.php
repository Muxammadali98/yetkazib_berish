<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Start extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const PAGE_SIZE = 20;
    public const TYPE_TEXT = 1;
    public const TYPE_PHOTO = 2;
    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 0;

    protected $attributes = [
        'status' => self::STATUS_INACTIVE
    ];

    public static function getType(): array
    {
        return [
            self::TYPE_TEXT => 'Text',
            self::TYPE_PHOTO => 'Photo'
        ];
    }

    public static function getStatus(): array
    {
        return [
            self::STATUS_ACTIVE => 'Faol',
            self::STATUS_INACTIVE => 'No faol'
        ];
    }
}
