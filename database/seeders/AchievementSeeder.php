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
            'badge' => 'https://res.cloudinary.com/detdiabum/image/upload/v1639759223/CG-Challenges-API/OneSmallSteo_igznk8.jpg',
        ]);

        Achievement::create([
            'name' => 'Half Done',
            'description' => 'Just begin!',
            'badge' => 'https://res.cloudinary.com/detdiabum/image/upload/v1639759226/CG-Challenges-API/HalfDone_c3d1l6.jpg',
        ]);

        Achievement::create([
            'name' => 'Sprint',
            'description' => 'Complete two task in one day',
            'badge' => 'https://res.cloudinary.com/detdiabum/image/upload/v1639759220/CG-Challenges-API/Sprint_okhrgk.jpg',
        ]);
    }
}
