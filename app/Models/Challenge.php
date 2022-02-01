<?php

namespace App\Models;

use App\Constants\ChallengeStatuses;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

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


    protected static function booted()
    {
        // I'm thinking that maybe we don't need create files.
        // Maybe its better just retrieve the code challenge from database

        static::created(function ($challenge) {
//            Storage::disk('local')->makeDirectory('ChallengesTests/javascript/' . $challenge->id);
//
//            chmod(storage_path('app/ChallengesTests/javascript/' . $challenge->id), 0766);


            Storage::disk('local')->put('ChallengesTests/javascript/'
                . $challenge->id
                . '/template.js',
                $challenge->func_template);
            chmod(storage_path('app/ChallengesTests/javascript/' . $challenge->id . '/template.js'), 0766);
        });

        static::updated(function ($challenge) {
            Storage::disk('local')->put('ChallengesTests/javascript/'
                . $challenge->id
                . '/template.js',
                $challenge->func_template);
        });

        //On delete won't delete file, because we are using soft deletes
    }
}
