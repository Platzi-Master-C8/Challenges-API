<?php

namespace Database\Seeders;

use App\Models\Rank;
use Illuminate\Database\Seeder;

class RankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rank::create([
            'name' => 'Novice',
            'required_points' => 0,
        ]);

        Rank::create([
            'name' => 'Proficient',
            'required_points' => 101,
        ]);

        Rank::create([
            'name' => 'Expert',
            'required_points' => 201,
        ]);
    }
}
