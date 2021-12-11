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

    public function test_get_challenger(): void
    {
        Carbon::setTestNow('2021-12-05');

        $user = User::factory()->create();
        $rank = Rank::factory()->create(['required_points' => 0]);

        Rank::factory()->create(['required_points' => 101]);
        Rank::factory()->create(['required_points' => 201]);

        $relatedAchievements = Achievement::factory()->count(2)->create();

        Achievement::factory()->count(8)->create();

        $challenges = Challenge::factory()->count(10)->create();
        $challenger = Challenger::create([
            'points' => 100,
            'user_id' => $user->id,
            'rank_id' => $rank->id
        ]);

        $relatedAchievements->each(function ($achievement) use ($challenger) {
            $challenger->achievements()->attach($achievement);
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
                    'nick_name',
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
                        'related' => [
                            [
                                'name',
                                'description',
                                'badge',
                            ],
                        ],
                        'non_related' => [
                            [
                                'name',
                                'description',
                                'badge',
                            ],
                        ],
                    ],
                    'activity' => [],
                ],
            ]);
    }
}
