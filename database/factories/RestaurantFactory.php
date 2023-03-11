<?php

namespace Database\Factories;

use App\Models\Restaurant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Restaurant>
 */
class RestaurantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Restaurant::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->company,
            'description' => $this->faker->sentence,
            'email' => $this->faker->unique()->email,
            'phone' => $this->faker->unique()->phoneNumber,
            'email_verified_at' => Carbon::now(),
            'address_line_1' => $this->faker->streetAddress,
            'vicinity' => $this->faker->city,
            'country' => $this->faker->country,
            'postal_code' => $this->faker->postcode,
            'lat' => $this->faker->latitude,
            'lng' => $this->faker->longitude,
            'billing_date' => 1,
            'open_hours' => [
                "Mon" => [
                    "open" => random_int(6, 12) . ":00",
                    "close" => random_int(18, 23) . ":00",
                ],
                "Tue" => [
                    "open" => random_int(6, 12) . ":00",
                    "close" => random_int(18, 23) . ":00",
                ],
                "Wed" => [
                    "open" => random_int(6, 12) . ":00",
                    "close" => random_int(18, 23) . ":00",
                ],
                "Thu" => [
                    "open" => random_int(6, 12) . ":00",
                    "close" => random_int(18, 23) . ":00",
                ],
                "Fri" => [
                    "open" => random_int(6, 12) . ":00",
                    "close" => random_int(18, 23) . ":00",
                ],
                "Sat" => [
                    "open" => random_int(6, 12) . ":00",
                    "close" => random_int(18, 23) . ":00",
                ],
                "Sun" => [
                    "open" => random_int(6, 12) . ":00",
                    "close" => random_int(18, 23) . ":00",
                ],
            ],
            'table_confirmation' => collect(['manual', 'automatic'])->random(),
            'interval' => 15,
            'booking_timeframe' => [
                'covers' => 8,
                'minutes' => 15,
            ],
            'status' => 'live',
            'turnaround_time' => 15,
            'show_days' => 50,
            'no_show_fee' => 0,
        ];
    }
}
