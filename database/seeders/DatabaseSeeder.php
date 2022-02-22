<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Storage::disk('local')->makeDirectory('ChallengesTests');
        chmod(storage_path('app/ChallengesTests'), 0777);
        Storage::disk('local')->makeDirectory('ChallengesTests/javascript');
        chmod(storage_path('app/ChallengesTests/javascript'), 0777);


        $this->call([
            RankSeeder::class,
            AchievementSeeder::class,
//            ChallengerSeeder::class,
            ChallengeSeeder::class,
        ]);
    }
}
