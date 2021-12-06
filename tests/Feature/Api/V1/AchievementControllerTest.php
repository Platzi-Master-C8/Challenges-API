<?php

namespace Tests\Feature\Api\V1;

use App\Models\Achievement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AchievementControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_achievements()
    {
        $response = $this->get(route('achievements.index'));
        $response->assertStatus(200);
    }

    public function test_get_achievement()
    {
        $achievement = Achievement::factory()->create();

        $response = $this->get(route('achievements.show', $achievement->id));
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description',
                'badge' => ['url'],
                'created_at',
                'updated_at',
            ],
        ]);
        $response->assertStatus(200);
    }


}
