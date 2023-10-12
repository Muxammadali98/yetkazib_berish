<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationComment extends Model
{
    use HasFactory;

    const STATUS_ACCEPTED = 1;
    const STATUS_REJECTED = 0;
    protected $guarded = [];

    public function getStatus(): string
    {
        // false berib yubordi hostingda localda tog'ri ishadi , typedan boldi
        return $this->status == self::STATUS_ACCEPTED
            ? '<span class="badge badge-success">Qabul qilindi</span>'
            : '<span class="badge badge-danger">Bekor qilindi</span>';
    }

    public function application(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
