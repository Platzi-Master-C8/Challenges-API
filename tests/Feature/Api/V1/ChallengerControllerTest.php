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

    private $challenger;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2021-12-05');

        $user = User::factory()->create();
        $rank = Rank::factory()->create(['required_points' => 0]);

        Rank::factory()->create(['required_points' => 101]);
        Rank::factory()->create(['required_points' => 201]);

        $relatedAchievements = Achievement::factory()->count(4)->create();

        Achievement::factory()->count(10)->create();

        $this->challenges = Challenge::factory()->count(100)->create();
        $this->challenger = Challenger::factory()
            ->for($user)
            ->for($rank)
            ->create(['points' => 100]);

        $relatedAchievements->each(function ($achievement) {
            $this->challenger->achievements()->attach($achievement);
        });

        $this->challenges->each(function ($challenge) {
            $this->challenger->challenges()->attach($challenge, [
                'status' => $this->faker->randomElement(ChallengeStatuses::toArray()),
                'created_at' => $createdAt = $this->faker->dateTimeBetween('-7 days'),
                'updated_at' => $createdAt,
            ]);
        });
    }

    public function test_get_challenger(): void
    {
        $response = $this->json(
            'GET',
            route('challengers.show', [
                'challenger' => $this->challenger->id,
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
                        [
                            'name',
                            'description',
                            'badge',
                            'is_complete',
                        ],
                    ],
                    'activity' => [],
                ],
            ]);
    }

    public function test_get_challenges(): void
    {
        $response = $this->json(
            'GET',
            route('challengers.challenges', [
                'challenger' => $this->challenger->id,
            ])
        );

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'name',
                        'description',
                    ],
                ],
            ]);
    }
}
