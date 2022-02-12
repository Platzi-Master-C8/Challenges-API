<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name(),
            'nick_name' => $this->faker->firstName(),
            'email' => $this->faker->unique()->safeEmail(),
            'profile_image' => $this->faker->imageUrl(),
            'sub' => $this->faker->uuid(),
        ];
    }
}
