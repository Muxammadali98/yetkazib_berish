<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdditionalNotice extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    const PAGE_SIZE = 20;
    const TYPE_TEXT = 0;
    const TYPE_WITH_IMAGE = 1;

    public static function getTypes(): array
    {
        return [
            self::TYPE_TEXT => 'Text',
            self::TYPE_WITH_IMAGE => 'Rasmli',
        ];
    }
    public function getStatus(): string
    {
        return $this->trashed() ? 'O\'chirilgan' : 'Mavjud';
    }

    public function getNotificationDataId(): int
    {
        return 4;
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
