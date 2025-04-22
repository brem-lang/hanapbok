<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resort extends Model
{
    protected $guarded = [];

    protected $casts = [
        'details' => 'array',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
