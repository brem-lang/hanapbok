<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    protected $guarded = [];

    protected $casts = [
        'otherInfo' => 'array',
    ];

    public function accommodations(): HasMany
    {
        return $this->hasMany(Accommodation::class, 'id', 'room_cottage_type');
    }
}
