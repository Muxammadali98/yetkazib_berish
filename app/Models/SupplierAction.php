<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierAction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function ticket(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function supplierFiles(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SupplierFile::class);
    }
}
