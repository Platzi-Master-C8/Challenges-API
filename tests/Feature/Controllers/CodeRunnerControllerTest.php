<?php

namespace Tests\Feature\Controllers;

use App\Models\Challenge;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CodeRunnerControllerTest extends TestCase
{
    use RefreshDatabase;


    /**
     * A basic feature test example.
     *
     * @return void
     */
    private $challenge;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->challenge = Challenge::create([
            'id' => 1,
            'name' => 'Challenge 1',
            'description' => 'Description 1',
            'timeout' => '15',
            'difficulty' => 'low',
            'func_template' => 'function test($input) {}',
            'test_template' => 'assert true xd',
            'created_at' => now(),
            'updated_at' => now()
        ]);

    }

    public function test_get_entire_challenge_resource()
    {


        $this->get('/api/runner/on/node/1')
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'description',
                'template',
                'instructions',
                'tests',
                'created_at',
                'updated_at',
            ]);
    }
}