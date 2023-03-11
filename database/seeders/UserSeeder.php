<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory([
            'name' => 'Sean Ross',
            'email' => 'info@mytables.co.uk',
            'password' => Hash::make('password'),
            'current_team_id' => 1,
        ])->create();

        User::factory()
            ->count(50)
            ->create();
    }
}
