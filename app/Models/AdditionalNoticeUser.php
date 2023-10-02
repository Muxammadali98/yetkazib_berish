<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalNoticeUser extends Model
{
    use HasFactory;

    protected $table = 'additional_notice_user';

    protected $guarded = [];

    const STATUS_READ = true;
    const STATUS_UNREAD = false;

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function additionalNotice(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AdditionalNotice::class);
    }
}
