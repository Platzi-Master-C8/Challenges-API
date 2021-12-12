<?php

namespace Database\Seeders;

use App\Constants\ChallengeStatuses;
use App\Models\Achievement;
use App\Models\Challenge;
use App\Models\Challenger;
use App\Models\Rank;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Seeder;

class ChallengerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        $maxPoints = Rank::max('required_points');
        $challengerPoints = rand(0, $maxPoints);
        $user = User::factory()->create(['nick_name' => 'John Doe']);
        $rank = Rank::where('required_points', '<=', $challengerPoints)->orderByDesc('id')->first();
        $relatedAchievements = Achievement::orderBy('id')->get(['id']);

        Achievement::factory()->count(10)->create();

        $challenger = Challenger::create([
            'points' => $challengerPoints,
            'user_id' => $user->id,
            'rank_id' => $rank->id,
        ]);

        $relatedAchievements->each(function ($achievement) use ($challenger) {
            $challenger->achievements()->attach($achievement);
        });

        $challenges = Challenge::factory()->count($challengerPoints * 2)->create();

        $challenges->each(function ($challenge) use ($challenger) {
            if ((bool)random_int(0, 1)) {
                $statusIndex = array_rand(ChallengeStatuses::toArray());
                $challenger->challenges()->attach($challenge, [
                    'status' => ChallengeStatuses::toArray()[$statusIndex],
                    'created_at' => $createdAt = Carbon::now()->subDays(rand(0, 7)),
                    'updated_at' => $createdAt,
                ]);
            }
        });
    }
}
