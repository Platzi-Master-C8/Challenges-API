<?php

namespace Database\Factories;

use App\Models\Rank;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChallengerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'points' => $this->faker->numberBetween(0, 255),
            'user_id' => User::factory(),
            'rank_id' => Rank::factory(),
        ];
    }
}
