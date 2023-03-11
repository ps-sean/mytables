<?php

namespace Database\Factories;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Sequence;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $end = random_int(18, 23);
        return [
            'restaurant_id' => Restaurant::query()->inRandomOrder()->first()->id,
            'title' => $this->faker->word,
            'description' => $this->faker->sentence,
            'start' => random_int(6, 12) . ":00:00",
            'finish' => $end . ":00:00",
            'last_booking' => $end - 1 . ":30:00",
        ];
    }
}
