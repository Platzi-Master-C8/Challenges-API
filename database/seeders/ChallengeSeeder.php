<?php

namespace Database\Seeders;

use App\Constants\ChallengeDifficulties;
use App\Models\Challenge;
use Illuminate\Database\Seeder;

class ChallengeSeeder extends Seeder
{
    public function run()
    {
        Challenge::create([
            "name" => "Pair",
            "description" => "Print the pair numbers from 1 to 100",
            "time_out" => 15,
            "difficulty" => ChallengeDifficulties::LOW,
            'func_template' => "function pair(){}\nmodule.exports=pair;",
            'test_template' => 'test template'
        ]);

        Challenge::create([
            "name" => "Odd",
            "description" => "Print the odd numbers from 1 to 100",
            "time_out" => 15,
            "difficulty" => ChallengeDifficulties::LOW,
            'func_template' => "function odd(\$n) {
                // your code here\n}\nmodule.exports=odd;",
            'test_template' => 'here is the test'
        ]);

        Challenge::create([
            "name" => "Fibonacci",
            "description" => "Code fibonacci sequence and print it!",
            "time_out" => 30,
            "difficulty" => ChallengeDifficulties::MEDIUM,
            'func_template' => "function fibonacci(\$n) {}\nmodule.exports=fibonacci;",
            'test_template' => 'Test template'
        ]);

        Challenge::create([
            "name" => "Palindrome",
            "description" => "A Palindrome is a word or phrase that reads the same in one sense as in another. You must make a function that returns true or false if a word is palindrome",
            "time_out" => 45,
            "difficulty" => ChallengeDifficulties::HIGH,
            'func_template' => "function palindrome(\$w){\n/*Your code here*/}" .
                "\nmodule.exports=palindrome;",

            'test_template' => 'Test template'
        ]);

        Challenge::create([
            "name" => "Sum",
            "description" => "Create a function that returns the sum of a + b",
            "time_out" => 45,
            "difficulty" => ChallengeDifficulties::LOW,
            'func_template' => "function sum(a,b){\n\t/*Your code here*/\n}" .
                "\nmodule.exports=sum;",

            'test_template' => "const func = require('./user_func.js');
            test('adds 1 + 2 to equal 3', () => {
    expect(func(1, 2)).toBe(3);
});"
        ]);

    }

}
