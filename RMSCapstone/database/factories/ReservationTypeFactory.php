<?php

namespace Database\Factories;

use App\Models\ReservationType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReservationType>
 */
class ReservationTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = ReservationType::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['house', 'room', 'event']),
        ];
    }
}
