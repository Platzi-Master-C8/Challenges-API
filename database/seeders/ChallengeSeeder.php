<?php

namespace Database\Seeders;

use App\Constants\ChallengeDifficulties;
use App\Models\Challenge;
use Illuminate\Database\Seeder;

class ChallengeSeeder extends Seeder
{
    public function run()
    {
        Challenge::factory()->create([
            "name" => "Odd",
            "description" => "Print the odd numbers from 1 to 100",
            "time_out" => 15,
            "difficulty" => ChallengeDifficulties::LOW
        ]);

        Challenge::factory()->create([
            "name" => "Fibonacci",
            "description" => "Code fibonacci sequence and print it!",
            "time_out" => 30,
            "difficulty" => ChallengeDifficulties::MEDIUM
        ]);

        Challenge::factory()->create([
            "name" => "Palindrome",
            "description" => "A Palindrome is a word or phrase that reads the same in one sense as in another. You must make a function that returns true or false if a word is palindrome",
            "time_out" => 45,
            "difficulty" => ChallengeDifficulties::HIGH
        ]);
    }
}
