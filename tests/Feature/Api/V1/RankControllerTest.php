<?php

namespace Tests\Feature\Api\V1;

use App\Models\Rank;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RankControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_ranks()
    {

        $response = $this->get('/api/V1/ranks');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
            'meta'
        ]);
    }

    public function test_get_rank()
    {
        $rank = Rank::factory()->create();
        $response = $this->get('/api/V1/ranks/' . $rank->id);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'required_points',
                'created_at',
                'updated_at',
            ],
        ]);
        $response->assertStatus(200);

    }

}
