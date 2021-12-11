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
                    $query->select('id', 'nick_name');
                },
                'rank' => function ($query) {
                    $query->select('id', 'name', 'required_points');
                },
                'achievements' => function ($query) {
                    $query->select('id', 'name', 'description', 'badge')->withPivot('created_at');
                },
                'challenges' => function ($query) {
                    $query->select('id')->withPivot('status', 'created_at', 'updated_at');
                },
            ])->firstOrFail(['id', 'points', 'user_id', 'rank_id']);
    }
}
