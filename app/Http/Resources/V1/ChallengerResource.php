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
                'current' => $this->currentRank(),
                'next' => $this->nextRank(),
            ],
            'achievements' => $this->getAchievements(),
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

    private function currentRank(): array
    {
        $currentRank = $this->ranks->firstWhere('pivot.is_current', true);

        return [
            'name' => $currentRank->name,
            'required_points' => $currentRank->required_points
        ];
    }

    private function nextRank(): array
    {
        $nextRank = $this->ranks->firstWhere('pivot.is_next', true);

        return [
            'name' => $nextRank->name,
            'required_points' => $nextRank->required_points
        ];
    }

    private function getAchievements(): array
    {
        return $this->achievements->map(function ($item) {
            return [
                'name' => $item['name'],
                'description' => $item['description'],
                'badge' => $item['badge'],
                'is_earned' => (bool)$item->getOriginal('pivot_is_earned'),
            ];
        })->toArray();
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
