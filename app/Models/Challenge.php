<?php

namespace App\Models;

use App\Constants\ChallengeStatuses;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Challenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'time_out', 'difficult'
    ];

    public function challengers(): BelongsToMany
    {
        return $this->belongsToMany(Challenger::class, 'challenge_activity_logs')
            ->withPivot('status');
    }

    public function getChallengersHasDefeatedAttribute(): int
    {
        return count($this->challengers->where('pivot.status', '=', ChallengeStatuses::COMPLETE));
    }
}