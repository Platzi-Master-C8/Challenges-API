<?php

namespace Tests\Feature\Api\V1;

use App\Models\Challenge;
use App\Models\Challenger;
use App\Models\Rank;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChallengesControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test endpoint "/api/v1/challenges"
     *
     * @return void
     */
    public function test_get_challenges()
    {

        $response = $this->get('/api/v1/challenges');
        $response->assertStatus(200);
        $response->assertJsonStructure(["data"]);
    }

    /**
     * Test endpoint "/api/v1/challenges/{id}"
     *
     * @return void
     */
    public function test_get_challenge()
    {

        //Create necessary user, challenger, challenge and rank to test json structure
        $user = User::factory()->create();
        $rank = Rank::factory()->create();
        $challenger = Challenger::create(
            ['user_id' => $user->id, 'rank_id' => $rank->id]
        );

        $challenge = Challenge::factory(1)->create();


        //Attach challenger to challenge to test a correct json response
        $challenger->challenges()->attach($challenge);
        $response = $this->get('/api/v1/challenges/1');
        $response->assertStatus(200);
        $response->assertJsonStructure(["data" => [
            'id',
            'name',
            'description',
            'time_out',
            'challengers_has_completed' => [['challenger', 'points', 'rank']],
        ]]);
    }
}
