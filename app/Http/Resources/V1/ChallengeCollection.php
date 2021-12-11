<?php

namespace App\Http\Resources\V1;

use App\Models\Challenge;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ChallengeCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return Challenge::all()->map(function ($challenge) {
            return [
                'id' => $challenge->id,
                'name' => $challenge->name,
                'description' => $challenge->description,
                'difficulty' => $challenge->difficulty,
                'challengers_defeated' => $challenge->challengersHasDefeated
            ];
        });
    }
}
