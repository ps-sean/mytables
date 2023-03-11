<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\RestaurantSection;
use App\Models\Service;
use App\Models\Table;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Restaurant::factory()
            ->count(10)
            ->has(User::factory()->count(random_int(1, 5)), 'staff')
            ->has(RestaurantSection::factory()->count(random_int(1, 3)), 'sections')
            ->has(Service::factory()
                ->count(7)
                ->state(new Sequence(
                    ['day' => 'Mon'],
                    ['day' => 'Tue'],
                    ['day' => 'Wed'],
                    ['day' => 'Thu'],
                    ['day' => 'Fri'],
                    ['day' => 'Sat'],
                    ['day' => 'Sun'],
                ))
            )
            ->has(Table::factory()
                ->state(function (array $attributes, Restaurant $restaurant) {
                    return [
                        'restaurant_section_id' => $restaurant->sections()->inRandomOrder()->first()->getKey(),
                    ];
                })
                ->count(random_int(5, 20)))
            ->create();
    }
}
