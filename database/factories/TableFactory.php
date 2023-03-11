<?php

namespace Database\Factories;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Table>
 */
class TableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $restaurant = Restaurant::query()->inRandomOrder()->first();

        return [
            'restaurant_id' => $restaurant->id,
            'restaurant_section_id' => $restaurant->sections()->inRandomOrder()->first()->id,
            'name' => $this->faker->word,
            'seats' => random_int(2, 10),
            'bookable' => true,
        ];
    }
}
