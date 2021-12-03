<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RankFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    static $points = 0;


    public function definition()
    {
        RankFactory::$points += 50;
        return [
            'name' => $this->faker->name,
            'required_points' => RankFactory::$points
        ];
    }
}
