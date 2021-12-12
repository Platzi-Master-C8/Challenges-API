<?php

namespace App\Http\Resources\V1;

use App\Constants\ChallengeStatuses;
use Illuminate\Http\Resources\Json\JsonResource;

class ChallengeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'challengers_has_completed' => $this->getChallengersHasCompleted()
        ];
    }

    private function getChallengersHasCompleted()
    {
        return $this->challengers->where('pivot.status', ChallengeStatuses::COMPLETE)
            ->map(function ($challenger) {
                return [
                    'nick_name' => $challenger->user->nick_name,
                    'points' => $challenger->points,
                    'rank' => $challenger->rank->name
                ];
            });
    }
}
