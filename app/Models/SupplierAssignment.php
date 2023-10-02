<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierAssignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    const PAGE_SIZE = 20;

    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;
    const STATUS_DONE = 3;


    public static function getStatus(): array
    {
        return [
            self::STATUS_PENDING => 'Kutilmoqda',
            self::STATUS_APPROVED => 'Qabul qilindi',
            self::STATUS_REJECTED => 'Rad etildi',
            self::STATUS_DONE => 'Bajarildi',
        ];
    }

    public function getNotificationDataId(): int
    {
        return 3;
    }

    public function isDone(): bool
    {
        return $this->status === self::STATUS_DONE;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function car(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Car::class);
    }
}
