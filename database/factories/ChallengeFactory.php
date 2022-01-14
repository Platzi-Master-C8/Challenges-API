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
            'func_template' => $this->func_template_generator(),
            'test_template' => $this->faker->text(20),
        ];
    }

    private function func_template_generator(): string
    {
        $funcName = substr($this->faker->word(), 0, -1);
        return "function $funcName(){\n" . "/*" . $this->faker->text(30)
            . "*/\n}\nmodule.exports= $funcName;";
    }


}
