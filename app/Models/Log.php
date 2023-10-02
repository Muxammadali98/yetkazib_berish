<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;
    const STATUS_ACCEPTED = 1;
    const STATUS_REJECTED = 2;

    const PAGE_SIZE = 20;

    protected $guarded = [];

    public static function getStatus(): array
    {
        return [
            self::STATUS_ACCEPTED => '<span class="badge badge-success">Qabul qilidi</span>',
            self::STATUS_REJECTED => '<span class="badge badge-danger">Rad etdi</span>'
        ];
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function application(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}
