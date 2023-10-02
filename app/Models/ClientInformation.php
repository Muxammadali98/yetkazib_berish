<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientInformation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const TYPE_TEXT = 0;
    public const TYPE_SELF_IMAGE = 1;
    public const TYPE_PASSPORT_IMAGE = 2;
    public const TYPE_PASSPORT_SELFIE_IMAGE = 3;
    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 1;

    public function client(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
