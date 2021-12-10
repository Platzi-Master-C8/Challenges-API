<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Achievement extends Model
{
    use HasFactory;

    public function challengers(): BelongsToMany
    {
        return $this->belongsToMany(Challenger::class, 'challenger_achievement')
            ->with('status');
    }
}