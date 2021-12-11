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

        $maxPoints = Rank::max('required_points');

        $user = User::factory()->create(['nick_name' => 'John Doe']);
        $achievements = Achievement::orderBy('id')->get(['id']);
        $challenges = Challenge::factory()->count(25)->create();

        $challengerPoints = rand(0, $maxPoints);


        $rank = Rank::where('required_points', '<=', $challengerPoints)
            ->orderBy('id', 'desc')->first();

        $challenger = Challenger::create(['points' => $challengerPoints,
            'user_id' => $user->id,
            'rank_id' => $rank->id
        ]);

        $achievements->each(function ($achievement) use ($challenger) {
            if (rand(0, 3) == 1) {
                $challenger->achievements()->attach($achievement->id, [
                    'created_at' => Carbon::now(),
                ]);
            }
        });


        $challenges->each(function ($challenge) use ($challenger) {
            if (rand(0, 1) == 1) {
                $statusIndex = array_rand(ChallengeStatuses::toArray());
                $challenger->challenges()->attach($challenge, [
                    'status' => ChallengeStatuses::toArray()[$statusIndex],
                    'created_at' => $createdAt = Carbon::now()->subDays(rand(0, 4)),
                    'updated_at' => $createdAt,
                ]);
            }
        });
    }
}
