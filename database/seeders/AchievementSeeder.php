<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Achievement::factory()->create([
            'name' => 'Sprint',
            'description' => 'Sprint description',
        ]);

        Achievement::factory()->create([
            'name' => 'One small step',
            'description' => 'One small step description',
        ]);

        Achievement::factory()->create([
            'name' => '5 days of code',
            'description' => '5 days of code description',
        ]);

        Achievement::factory()->create([
            'name' => 'Half Done',
            'description' => 'Half Done description',
        ]);
    }
}
