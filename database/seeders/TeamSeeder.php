<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Team::query()->create([
            'user_id' => 1,
            'name' => 'myTables Staff',
            'personal_team' => 0
        ]);

        Team::query()->create([
            'user_id' => 1,
            'name' => 'customers',
            'personal_team' => 0
        ]);
    }
}
