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

    public function currentRank(): BelongsTo
    {
        return $this->belongsTo(Rank::class); //TODO: Query
    }

    public function nextRank(): BelongsTo
    {
        return $this->belongsTo(Rank::class); //TODO: Query
    }

    public function relatedAchievements(): BelongsToMany
    {
        return $this->belongsToMany(Achievement::class)->withPivot('created_at'); //TODO: Query
    }

    public function nonRelatedAchievements(): BelongsToMany
    {
        return $this->belongsToMany(Achievement::class)->withPivot('created_at'); //TODO: Query
    }

    public function challenges(): BelongsToMany
    {
        return $this->belongsToMany(Challenge::class, 'challenge_activity_logs')
            ->withPivot('status')
            ->withTimestamps();
    }
}
