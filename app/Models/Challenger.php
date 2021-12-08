<?php

namespace App\Models;

use App\Constants\ChallengeStatuses;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Challenger extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ranks(): BelongsToMany
    {
        return $this->belongsToMany(Rank::class)
            ->withPivot('is_current', 'is_next');
    }

    public function achievements(): BelongsToMany
    {
        return $this->belongsToMany(Achievement::class)
            ->withPivot('is_earned', 'created_at');
    }

    public function challenges(): BelongsToMany
    {
        return $this->belongsToMany(Challenge::class, 'challenge_activity_logs')
            ->withPivot('status')
            ->withTimestamps();
    }
}
