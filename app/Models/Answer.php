<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const MANAGER_NEW_ORDER = 1;
    const STATUS_MANAGER_ACCEPTED = 2;
    const STATUS_MANAGER_REJECTED = 3;
    const CONTRACT_NEW_ORDER = 2;
    const STATUS_CONTRACT_ACCEPTED = 4;
    const STATUS_CONTRACT_REJECTED = 5;

    const LANGUAGE_UZ = 1;
    const LANGUAGE_RU = 2;

    const PAGE_SIZE = 20;

    protected $guarded = [];

    public function question(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function client(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function region(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}
