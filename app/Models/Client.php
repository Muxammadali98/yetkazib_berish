<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    const PAGE_SIZE = 20;

    protected $guarded = [];

    protected $attributes = [
        'status' => self::STATUS_INACTIVE,
    ];

    public function clientInformation(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClientInformation::class);
    }

    public function answers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function applications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Application::class);
    }

}
