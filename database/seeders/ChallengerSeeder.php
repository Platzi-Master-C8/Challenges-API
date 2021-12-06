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
use Illuminate\Foundation\Testing\WithFaker;

class ChallengerSeeder extends Seeder
{
    use WithFaker;

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        $user = User::factory()->create(['name' => 'John Doe']);
        $ranks = Rank::orderBy('required_points')->take(2)->get(['id', 'required_points']);
        $currentRank = $ranks->first();
        $nextRank = $ranks->sortByDesc('required_points')->first();
        $achievements = Achievement::orderBy('id')->get(['id']);
        $challenges = Challenge::factory()->count(25)->create();
        $challenger = Challenger::factory()
            ->for($user)
            ->hasAttached($currentRank, ['is_current' => true])
            ->hasAttached($nextRank, ['is_next' => true])
            ->create(['points' => 50]);

        $achievements->each(function ($achievement) use ($challenger) {
            $challenger->achievements()->attach($achievement, [
                'is_earned' => (bool)random_int(0, 1)
            ]);
        });

        $challenges->each(function ($challenge) use ($challenger) {
            $statusIndex = array_rand(ChallengeStatuses::toArray());
            $challenger->challenges()->attach($challenge, [
                'status' => ChallengeStatuses::toArray()[$statusIndex],
                'created_at' => $createdAt = Carbon::now()->subDays(rand(0, 4)),
                'updated_at' => $createdAt,
            ]);
        });
    }
}
