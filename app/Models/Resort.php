<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resort extends Model
{
    protected $guarded = [];

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }
}
