<?php

namespace App\Http\Resources\V1;

use App\Constants\ChallengeStatuses;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ChallengeResource extends JsonResource
{
    public function toArray($request): array
    {
        $max = $request->query('max', 10);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'time_out' => $this->time_out,
            'challengers_has_completed' => $this->getChallengersHasCompleted($max),
        ];
    }

    private function getChallengersHasCompleted($max): Collection
    {
        $challenger_has_completed = DB::table('challenge_activity_logs')->where('challenge_id', $this->id)
            ->where('challenge_id', '=', $this->id)
            ->join('challengers', 'challengers.id', '=', 'challenge_activity_logs.challenger_id')
            ->join('users', 'users.id', '=', 'challengers.user_id')
            ->join('ranks', 'ranks.id', '=', 'challengers.rank_id')
            ->select('users.nick_name as user', 'points', 'ranks.name as rank')
            ->limit($max)
            ->get();

        return $challenger_has_completed->map(function ($challenger) {
            return [
                'challenger' => $challenger->user,
                'points' => $challenger->points,
                'rank' => $challenger->rank
            ];
        });


    }
}
