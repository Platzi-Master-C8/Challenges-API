<?php

namespace App\Http\Resources\V1;

use App\Constants\ChallengeStatuses;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class ChallengerResource extends JsonResource
{
    public function toArray($request): array
    {
        $dateFrom = Carbon::create($request->get('dateFrom') ?? now()->subWeek());
        $dateTo = Carbon::create($request->get('dateTo') ?? now());

        return [
            'id' => $this->id,
            'name' => $this->user->name,
            'points' => $this->points,
            'challenges' => [
                'completed' => $this->completedChallengesAmount(),
                'streak' => $this->challengeStreakAmount()
            ],
            'ranks' => [
                'current' => $this->getCurrentRank(),
                'next' => $this->getNextRank(),
            ],
            'related_achievements' => $this->getRelatedAchievements(),
            'non_related_achievements' => $this->getRelatedAchievements(),
            'activity' => $this->getActivity($dateFrom, $dateTo),
        ];
    }

    private function completedChallengesAmount(): int
    {
        return $this->getCompletedChallenges()->count();
    }

    private function challengeStreakAmount(): int
    {
        return $this->getCompletedChallenges()->filter(function ($value) {
                return date("Y-m-d", strtotime(now())) === date("Y-m-d", strtotime($value->pivot->updated_at));
            })->count();
    }

    private function getCurrentRank(): array
    {
        $currentRank = $this->currentRank;

        return [
            'name' => $currentRank->name,
            'required_points' => $currentRank->required_points
        ]; //TODO: Refactor
    }

    private function getNextRank(): array
    {
        $nextRank = $this->nextRank;

        return [
            'name' => $nextRank->name,
            'required_points' => $nextRank->required_points
        ]; //TODO: Refactor
    }

    private function getRelatedAchievements(): array
    {
        return $this->relatedAchievements->map(function ($item) {
            return [
                'name' => $item['name'],
                'description' => $item['description'],
                'badge' => $item['badge'],
            ];
        })->toArray(); //TODO: Refactor
    }

    private function getNonRelatedAchievements(): array
    {
        return $this->nonRelatedAchievements->map(function ($item) {
            return [
                'name' => $item['name'],
                'description' => $item['description'],
                'badge' => $item['badge'],
            ];
        })->toArray(); //TODO: Refactor
    }

    private function getActivity(Carbon $dateFrom, Carbon $dateTo): array
    {
        return $this->getCompletedChallenges()
            ->whereBetween('pivot.updated_at', [$dateFrom, $dateTo])
            ->map(function ($item) {
                return [
                    'status' => $item->getOriginal('pivot_status'),
                    'updated_at' => date("Y-m-d", strtotime($item->getOriginal('pivot_updated_at'))),
                ];
            })->groupBy('updated_at')->map(function ($item) {
                return $item->count();
            })->toArray();
    }

    private function getCompletedChallenges(): Collection
    {
        return $this->challenges->where('pivot.status', ChallengeStatuses::COMPLETE);
    }
}
