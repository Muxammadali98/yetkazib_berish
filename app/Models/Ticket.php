<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_OPEN = 0;
    const STATUS_CLOSED = 1;
    const STATUS_IN_PROGRESS = 2;
    const STATUS_ON_HOLD = 3;
    const STATUS_CANCELLED = 4;
    const TYPE_NEW = 0;
    const TYPE_RETURN = 1;

    const PAGE_SIZE = 20;

    protected $guarded = [];

    public static function getStatus(): array
    {
        return [
            self::STATUS_OPEN => 'Yangi',
            self::STATUS_CLOSED => 'Tugallangan',
            self::STATUS_IN_PROGRESS => 'Jarayonda',
            self::STATUS_ON_HOLD => 'Kutilmoqda',
            self::STATUS_CANCELLED => 'Bekor qilingan',
        ];
    }

    public static function getTypes(): array
    {
        return [
            self::TYPE_NEW => 'Yangi',
            self::TYPE_RETURN => 'Qaytarilgan',
        ];
    }

    public function getNotificationDataId(): int
    {
        if ($this->type === self::TYPE_NEW) {
            return 1;
        }

        return 2;
    }

    public function getNotificationTitle(): string
    {
        if ($this->type === self::TYPE_NEW) {
            return __('api-custom.new-order');
        }

        return __('api-custom.return-order');
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductData::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function supplierAction(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(SupplierAction::class);
    }

    public function supplierFiles(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SupplierFile::class);
    }

    public function car(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Car::class);
    }
}
