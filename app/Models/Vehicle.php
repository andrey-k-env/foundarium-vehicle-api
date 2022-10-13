<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;


class Vehicle extends Model
{
    use HasFactory;

    public function driver(): belongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
