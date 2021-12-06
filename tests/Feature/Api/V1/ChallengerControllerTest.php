<?php

namespace Tests\Feature\Api\V1;

use App\Constants\ChallengeStatuses;
use App\Models\Achievement;
use App\Models\Challenge;
use App\Models\Challenger;
use App\Models\Rank;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChallengerControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_it_can_get_a_challenger_data(): void
    {
        Carbon::setTestNow('2021-12-05');

        $user = User::factory()->create();
        $currentRank = Rank::factory()->create();
        $nextRank = Rank::factory()->create();
        $achievements = Achievement::factory()->count(4)->create();
        $challenges = Challenge::factory()->count(10)->create();
        $challenger = Challenger::factory()
            ->for($user)
            ->hasAttached($currentRank, ['is_current' => true])
            ->hasAttached($nextRank, ['is_next' => true])
            ->create();

        $achievements->each(function ($achievement) use ($challenger) {
            $challenger->achievements()->attach($achievement, [
                'is_earned' => $this->faker->randomElement([true, false])
            ]);
        });

        $challenges->each(function ($challenge) use ($challenger) {
            $challenger->challenges()->attach($challenge, [
                'status' => $this->faker->randomElement(ChallengeStatuses::toArray()),
                'created_at' => $createdAt = $this->faker->dateTimeBetween('-4 days'),
                'updated_at' => $createdAt,
            ]);
        });

        $response = $this->json(
            'GET',
            route('challengers.show', [
                'challenger' => $challenger->id,
                'dateFrom' => '2021-12-01',
                'dateTo' => '2021-12-05',
            ])
        );

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'points',
                    'challenges' => [
                        'completed',
                        'streak',
                    ],
                    'ranks' => [
                        'current' => [
                            'name',
                            'required_points',
                        ],
                        'next' => [
                            'name',
                            'required_points',
                        ],
                    ],
                    'achievements' => [
                        [
                            'name',
                            'description',
                            'badge',
                            'is_earned',
                        ],
                    ],
                    'activity' => [],
                ],
            ]);
    }
}
