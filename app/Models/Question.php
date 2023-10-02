<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const PAGE_SIZE = 20;
    public const TYPE_WITH_A_BUTTON = 1;
    public const TYPE_NO_BUTTON = 2;
    public const TYPE_PHOTO = 3;
    public const TYPE_PHONE_NUMBER = 4;
    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 0;

    protected $attributes = [
        'type' => self::TYPE_NO_BUTTON,
        'status' => self::STATUS_ACTIVE
    ];

    protected static function boot()
    {
        parent::boot();
        $model = self::orderByDesc('step')->first();
        static::creating(function ($query) use ($model) {
            $query->step = isset($model->step) ? $model->step + 1 : 1;
        });
    }

    public static function getType(): array
    {
        return [
            self::TYPE_NO_BUTTON => 'Tugmasiz',
            self::TYPE_WITH_A_BUTTON => 'Tugmali',
            self::TYPE_PHOTO => 'Mijoz rasm yuborishi kerak',
            self::TYPE_PHONE_NUMBER => 'Mejoz Telefon raqam yuborishi kerak',
        ];
    }

    public static function getStatus(): array
    {
        return [
            self::STATUS_ACTIVE => 'Faol',
            self::STATUS_INACTIVE => 'No faol'
        ];
    }

    public function buttons(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Button::class);
    }

    public function temp(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Temp::class);
    }

    public function answers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Answer::class);
    }
}
