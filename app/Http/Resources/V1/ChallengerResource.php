<?php

namespace App\Http\Resources\V1;

use App\Constants\ChallengeStatuses;
use App\Models\Achievement;
use App\Models\Rank;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ChallengerResource extends JsonResource
{
    private const NON_RELATED_ACHIEVEMENTS_AMOUNT = 2;

    public function toArray($request): array
    {
        $dateFrom = Carbon::create($request->get('dateFrom') ?? now()->subWeek());

        $dateTo = Carbon::create($request->get('dateTo') ?? now());

        return [
            'id' => $this->id,
            'nick_name' => $this->user->nick_name,
            'points' => $this->points,
            'challenges' => [
                'completed' => $this->completedChallengesAmount(),
                'streak' => $this->challengeStreakAmount()
            ],
            'ranks' => [
                'current' => $this->getCurrentRank(),
                'next' => $this->getNextRank(),
            ],
            'achievements' => [
                'related' => $this->getRelatedAchievements(),
                'non_related' => $this->getNonRelatedAchievements(),
            ],
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
        return [
            'name' => $this->rank->name,
            'required_points' => $this->rank->required_points
        ];
    }

    private function getNextRank(): array //TODO: Cache layer
    {
        $nextRank = DB::table('ranks')
            ->where('required_points', '>', $this->points)
            ->orderBy('required_points')
            ->first(['name', 'required_points']);

        return [
            'name' => optional($nextRank)->name,
            'required_points' => optional($nextRank)->required_points
        ];
    }

    private function getRelatedAchievements(): array
    {
        $relatedAchievements = $this->achievements;

        return $relatedAchievements->map(function ($item) {
            return [
                'name' => $item['name'],
                'description' => $item['description'],
                'badge' => $item['badge'],
            ];
        })->toArray();
    }

    private function getNonRelatedAchievements(): array //TODO: Cache layer
    {
        $nonRelatedAchievements = DB::table('achievements')
            ->whereNotIn('id', $this->achievements->pluck('id'))
            ->orderByDesc('created_at')
            ->limit(self::NON_RELATED_ACHIEVEMENTS_AMOUNT)
            ->get(['name', 'description', 'badge']);

        return $nonRelatedAchievements->map(function ($item) {
            return [
                'name' => $item->name,
                'description' => $item->description,
                'badge' => $item->badge,
            ];
        })->toArray();
    }

    private function getActivity(Carbon $dateFrom, Carbon $dateTo): array
    {
        return $this->getCompletedChallenges()
            ->whereBetween('pivot.updated_at', [$dateFrom, $dateTo->addDay()])
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
