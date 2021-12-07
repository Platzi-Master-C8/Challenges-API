<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ChallengerResource;
use App\Models\Challenger;

class ChallengerController extends Controller
{
    public function show(int $challengerId): ChallengerResource
    {
        return new ChallengerResource($this->getChallenger($challengerId));
    }

    private function getChallenger(int $challengerId): Challenger
    {
        return Challenger::where('id', $challengerId)
            ->with([
                'user' => function ($query) {
                    $query->select('id', 'name');
                },
                'ranks' => function ($query) {
                    $query->select('name', 'required_points')->withPivot('is_current', 'is_next');
                },
                'achievements' => function ($query) {
                    $query->select('name', 'description', 'badge')->withPivot('is_earned');
                },
                'challenges' => function ($query) {
                    $query->select('difficulty')->withPivot('status', 'updated_at');
                },
            ])->firstOrFail(['id', 'points', 'user_id']);
    }
}