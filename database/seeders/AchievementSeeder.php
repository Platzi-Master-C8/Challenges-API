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
            'name' => 'One small step',
            'description' => 'Complete your first task',
            'badge' => 'https://res.cloudinary.com/dckunlwcb/image/upload/v1639763361/OneSmallSteo_xs4wev.jpg',
        ]);

        Achievement::create([
            'name' => 'Half Done',
            'description' => 'Just begin!',
            'badge' => 'https://res.cloudinary.com/dckunlwcb/image/upload/v1639763365/HalfDone_rb6qma.jpg',
        ]);

        Achievement::create([
            'name' => 'Sprint',
            'description' => 'Complete two task in one day',
            'badge' => 'https://res.cloudinary.com/dckunlwcb/image/upload/v1639763357/Sprint_k1ekil.jpg',
        ]);

        Achievement::create([
            'name' => '5 days of code',
            'description' => 'Complete 5 days of code',
            'badge' => 'https://res.cloudinary.com/dckunlwcb/image/upload/v1639763358/5DaysOfCode_kohn4t.jpg',
        ]);
    }
}
