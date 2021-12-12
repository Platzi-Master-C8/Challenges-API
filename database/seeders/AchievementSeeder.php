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
        Achievement::create([
            'name' => 'Sprint',
            'description' => 'Sprint description',
            'badge' => 'https://via.placeholder.com/640x480.png/98CA3F?text=Sprint',
        ]);

        Achievement::create([
            'name' => 'One small step',
            'description' => 'One small step description',
            'badge' => 'https://via.placeholder.com/640x480.png/121F3D?text=Step',
        ]);

        Achievement::create([
            'name' => '5 days of code',
            'description' => '5 days of code description',
            'badge' => 'https://via.placeholder.com/640x480.png/F72201?text=Code',
        ]);

        Achievement::create([
            'name' => 'Half Done',
            'description' => 'Half Done description',
            'badge' => 'https://via.placeholder.com/640x480.png/181818?text=Done',
        ]);
    }
}
