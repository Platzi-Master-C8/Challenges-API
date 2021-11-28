<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Challenger;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChallengerControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fetch_challenger()
    {
        $response = $this->get('api/V1/challengers');
        $response->assertStatus(200);
    }

    public function test_create_challenger()
    {
        $response = $this->post('api/V1/challengers', [
            'nickname' => 'Run']);
        $response->assertStatus(201);

        $this->assertDatabaseHas('challengers', [
            'nickname' => 'Run']);

    }

    public function test_create_challenger_validation_fail()
    {
        $response = $this->post('api/V1/challengers', [
            'nickname' => 'As']);
        $response->assertSessionHasErrors(['nickname']);

        $this->assertDatabaseMissing('challengers', [
            'nickname' => 'As']);
    }

    public function test_show_challenger()
    {
        $challenger = Challenger::factory()->create();

        $response = $this->get("api/V1/challengers/$challenger->id");
        $response->assertStatus(200);
        $response->assertJson(["Challenger" => $challenger->toArray()]);

    }

    public function test_show_challenger_not_found()
    {
        $response = $this->get("api/V1/challengers/1");
        $response->assertStatus(404);
    }

    public function test_update_challenger()
    {
        $challenger = Challenger::factory()->create();

        $response = $this->put("api/V1/challengers/$challenger->id", [
            'nickname' => 'Run']);
        $response->assertStatus(200);

        $this->assertDatabaseHas('challengers', [
            'nickname' => 'Run']);
    }

    public function test_update_challenger_not_found()
    {
        $response = $this->put("api/V1/challengers/1", [
            'nickname' => 'Run']);
        $response->assertStatus(404);
    }

    public function test_destroy_challenger()
    {
        $challenger = Challenger::factory()->create();
        $this->assertDatabaseHas('challengers', ['nickname' => $challenger->nickname]);

        $response = $this->delete("api/V1/challengers/$challenger->id");
        $response->assertStatus(204);
        $this->assertDatabaseMissing("challengers", ['nickname' => $challenger->nickname, 'deleted_at' => null]);

    }

}
