<?php

namespace Database\Factories;

use App\Constants\ChallengeDifficulties;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChallengeFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->text(25),
            'description' => $this->faker->text(150),
            'time_out' => $this->faker->numberBetween(1, 4) * 15,
            'difficulty' => $this->faker->randomElement(ChallengeDifficulties::toArray()),
        ];
    }
}
