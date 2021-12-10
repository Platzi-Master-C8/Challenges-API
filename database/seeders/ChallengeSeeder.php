<?php

namespace Database\Seeders;

use App\Constants\ChallengeDifficulties;
use Illuminate\Database\Seeder;

class ChallengeSeeder extends Seeder
{
    //Seed to run in prod environment
    //TODO: Create more
    public function run()
    {
        $difficulties = ChallengeDifficulties::toArray();
        $challenge = ["name" => "Fibonacci",
            "description" => "Code fibonacci sequence and print it!",
            "time_out" => rand(1, 4) * 15,
            "difficulty" => $difficulties[array_rand($difficulties)]];
    }
}
