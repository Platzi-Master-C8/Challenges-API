<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Constants\ChallengeStatuses;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ChallengeChallengerCollection;
use App\Http\Resources\V1\ChallengerResource;
use App\Models\Challenger;

class ChallengerController extends Controller //TODO: Validations
{
    public function show(int $challengerId): ChallengerResource
    {
        return new ChallengerResource($this->getChallenger($challengerId));
    }

    public function challenges(int $challengerId): ChallengeChallengerCollection
    {
        return new ChallengeChallengerCollection($this->getCompletedChallenges($challengerId));
    }

    private function getChallenger(int $challengerId): Challenger //TODO: Query optimization
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

    private function getCompletedChallenges(int $challengerId) //TODO: Query optimization
    {
        $challenger = Challenger::where('id', $challengerId)
            ->with([
                'challenges' => function ($query) {
                    $query->select('id', 'name', 'description')->withPivot('status');
                },
            ])->firstOrFail('id');

        return $challenger->challenges->where('pivot.status', ChallengeStatuses::COMPLETE);
    }


    public function store(Request $request): array
    {
        $challenger = new Challenger();
        $challenger->user_id = $request->user()->id;
        $challenger->points = 0;
        $challenger->rank_id = 1;
        return $challenger->save() ? ['success' => true] : ['success' => false];
    }

}
