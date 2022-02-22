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
            'func_template' => "
function minsOnPhone(min1, min2_10, min11, s) {
    // your code here

}

module.exports = minsOnPhone;",
            'test_template' => "const reto5 = require('./user_func.js');

test('What is the duration of the longest call ', () => {
  expect(reto5(3,1,2,20)).toBe(14)
});
test('What is the duration of the longest call ', () => {
  expect(reto5(2,2,1,2)).toBe(1)
});
test('What is the duration of the longest call ', () => {
  expect(reto5(10,1,2,22)).toBe(11)
});
test('What is the duration of the longest call ', () => {
  expect(reto5(2,2,1,24)).toBe(14)
});
test('What is the duration of the longest call ', () => {
  expect(reto5(1,2,1,6)).toBe(3)
});"
        ]);

        Challenge::create([
            "name" => "Odd",
            "description" => "Print the odd numbers from 1 to 100",
            "time_out" => 15,
            "difficulty" => ChallengeDifficulties::LOW,
            'func_template' => "function minsOnRide(number) {
            // your code here
            }
",
            'test_template' => "const reto4 = require('./user_func.js')

test('calculate the current time ', () => {
  expect(reto4(240)).toBe(4)
});
test('calculate the current time ', () => {
  expect(reto4(1439)).toBe(19)
});
test('calculate the current time ', () => {
  expect(reto4(8)).toBe(8)
});
test('calculate the current time ', () => {
  expect(reto4(808)).toBe(14)
});
test('calculate the current time ', () => {
  expect(reto4(0)).toBe(0)
});"
        ]);

        Challenge::create([
            "name" => "Fibonacci",
            "description" => "Code fibonacci sequence and print it!",
            "time_out" => 30,
            "difficulty" => ChallengeDifficulties::MEDIUM,
            'func_template' => "
function largestProduct(array) {
 // your code here
}

module.exports = largestProduct;
            ",
            'test_template' => "const reto3 = require('./user_func.js');

test('find the pair of adjacent elements that has the largest product', () => {
  expect(reto3([3, 6, -2, -5, 7, 3])).toBe(21);
})
test('find the pair of adjacent elements that has the largest product', () => {
  expect(reto3([5, 1, 2, 3, 1, 4])).toBe(6);
})
test('find the pair of adjacent elements that has the largest product', () => {
  expect(reto3([1, 0, 1, 0, 1000])).toBe(0);
})
test('find the pair of adjacent elements that has the largest product', () => {
  expect(reto3([9, 5, 10, 2, 24, -1, -48])).toBe(50);
})
test('find the pair of adjacent elements that has the largest product', () => {
  expect(reto3([-23, 4, -3, 8, -12])).toBe(-12);
})"
        ]);

        Challenge::create([
            "name" => "Palindrome",
            "description" => "A Palindrome is a word or phrase that reads the same in one sense as in another. You must make a function that returns true or false if a word is palindrome",
            "time_out" => 45,
            "difficulty" => ChallengeDifficulties::HIGH,
            'func_template' => "function palindromo(string) {
                // your code here
            }\nmodule.exports=palindromo;",

            'test_template' => "
const reto2 = require('./user_func.js');
test('Given the string, check if it is palindrome', () => {
  expect(reto2('aabaa')).toBe(true);
});
test('Given the string, check if it is palindrome', () => {
  expect(reto2('a')).toBe(true);
});
test('Given the string, check if it is palindrome', () => {
  expect(reto2('abacaba')).toBe(true);
});
test('Given the string, check if it is palindrome', () => {
  expect(reto2('aaabaaaa')).toBe(false);
});
test('Given the string, check if it is palindrome', () => {
  expect(reto2('hlbeeykoqqqokyeeblh')).toBe(true);
});"
        ]);

        Challenge::create([
            "name" => "Sum",
            "description" => "Create a function that returns the sum of a + b",
            "time_out" => 45,
            "difficulty" => ChallengeDifficulties::LOW,
            'func_template' => "function sum(a,b){\n\t/*Your code here*/\n}" .
                "\nmodule.exports=sum;",

            'test_template' => "const reto1 = require('./user_func.js');

test('adds 1 + 2 to equal 3', () => {
  expect(reto1(1, 2)).toBe(3);
});
test('0 + 1000', () => {
  expect(reto1(0, 1000)).toBe(1000);
});
test('2 + -39', () => {
  expect(reto1(2, -39)).toBe(-37);
});
test('99 + 100', () => {
  expect(reto1(99, 100)).toBe(199);
});
test('-1000 + -1000', () => {
  expect(reto1(-1000, -1000)).toBe(-2000);
});"
        ]);

    }

}
