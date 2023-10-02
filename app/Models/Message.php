<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $guarded = [];

    const PAGE_SIZE = 20;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const TYPE_MANAGER_ACCEPTED = 1;
    const TYPE_MANAGER_REJECTED = 0;
    const TYPE_CONTRACT_ACCEPTED = 3;
    const TYPE_CONTRACT_REJECTED = 2;

    protected $attributes = [
        'status' => self::STATUS_INACTIVE
    ];

    public static function getStatus(): array
    {
        return [
            self::STATUS_ACTIVE => 'Faol',
            self::STATUS_INACTIVE => 'No faol'
        ];
    }

    public static function getType(): array
    {
        return [
            self::TYPE_MANAGER_ACCEPTED => 'Menedjer tasdiqladi',
            self::TYPE_MANAGER_REJECTED => 'Menedjer rad etdi',
            self::TYPE_CONTRACT_ACCEPTED => 'Shartnomachi tasdiqladi',
            self::TYPE_CONTRACT_REJECTED => 'Shartnomachi rad etdi'
        ];
    }
}
