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

    public function test_store_challenge()
    {
        $data = [
            'name' => 'random',
            'description' => 'Get a random number',
            'time_out' => '15',
            'difficulty' => 'low',
            'func_template' => 'function random($min, $max) {
                return rand($min, $max);
            }',
            'test_template' => 'function test($min, $max) {
                return random($min, $max) == rand($min, $max);
            }',

        ];

        $response = $this->post('/api/v1/challenges', array_merge($data, ['token' => 'h2405kal2rnk123']));
        $response->assertStatus(201);

        $response->assertJsonStructure(["data" => [
            'id',
            'name',
            'description',
            'time_out',
            'challengers_has_completed' => [],
        ]]);
        $this->assertDatabaseHas('challenges', $data);
    }
}
