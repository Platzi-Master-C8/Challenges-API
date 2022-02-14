<?php

namespace App\Http\Resources\V1;

use App\Constants\ChallengeStatuses;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ChallengerResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'nick_name' => $this->user->nick_name,
            'points' => $this->points,
            'challenges' => [
                'completed' => $this->getCompletedChallengesAmount(),
                'streak' => $this->getChallengesStreak()
            ],
            'ranks' => [
                'current' => $this->getCurrentRank(),
                'next' => $this->getNextRank(),
            ],
            'achievements' => $this->getAchievements(),
            'activity' => $this->getLastWeekActivity(),
        ];
    }

    private function getCompletedChallengesAmount(): int
    {
        return $this->getCompletedChallenges()->count();
    }

    private function getChallengesStreak(): int
    {
        $lastDateOfActivity = date_create($this->getActivity()->keys()->first());
        $currentDate = date_create(date('Y-m-d', strtotime(now())));
        $streak = 0;

        if (date_diff($currentDate, $lastDateOfActivity)->days > 1) {
            return $streak;
        }

        $activityDays = $this->getActivityDays();

        for ($i = 0; $i < count($activityDays) - 1; $i++) {
            if ($activityDays[$i] - $activityDays[$i + 1] != 1) {
                break;
            }

            $streak++;
        }

        return $streak + 1;
    }

    private function getCurrentRank(): array
    {
        return [
            'name' => $this->rank->name,
            'required_points' => $this->rank->required_points,
        ];
    }

    private function getNextRank(): array
    {
        $nextRank = DB::table('ranks')
            ->where('required_points', '>', $this->points)
            ->orderBy('required_points')
            ->first(['name', 'required_points']);

        return [
            'name' => optional($nextRank)->name,
            'required_points' => optional($nextRank)->required_points,
        ];
    }

    private function getAchievements(): array
    {
        $achievements = DB::table('achievements')
            ->orderByDesc('created_at')
            ->get(['id', 'name', 'description', 'badge']);

        return $achievements->map(function ($item) {
            return [
                'name' => $item->name,
                'description' => $item->description,
                'badge' => $item->badge,
                'is_complete' => in_array($item->id, $this->achievements->pluck('id')->toArray()),
            ];
        })->toArray();
    }

    private function getLastWeekActivity(): array
    {
        return $this->getActivity()->takeUntil(function ($item, $date) {
            return $date < date('Y-m-d', strtotime(now()->subWeek()));
        })->toArray();
    }

    private function getActivityDays(): array
    {
        return $this->getActivity()->map(function ($item, $date) {
            return substr($date, -2);
        })->values()->toArray();
    }

    private function getActivity(): Collection
    {
        return $this->getCompletedChallenges()
            ->map(function ($item) {
                return [
                    'updated_at' => date('Y-m-d', strtotime($item->getOriginal('pivot_updated_at'))),
                ];
            })
            ->sortByDesc('updated_at')
            ->groupBy('updated_at')
            ->map(function ($item) {
                return $item->count();
            });
    }

    private function getCompletedChallenges(): Collection
    {
        return $this->challenges->where('pivot.status', ChallengeStatuses::COMPLETE);
    }
}
