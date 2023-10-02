<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    const PAGE_SIZE = 20;
    const STATUS_ACTIVE = 0;
    const STATUS_INACTIVE = 1;

    public function getStatus(): string
    {
        return $this->status === self::STATUS_ACTIVE
            ? '<span class="badge badge-success">Aktiv</span>'
            : '<span class="badge badge-danger">Band</span>';
    }

    public function region(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'car_user');
    }

    public function getUsers(): array
    {
        return $this->users()->pluck('user_id')->toArray();
    }
}
