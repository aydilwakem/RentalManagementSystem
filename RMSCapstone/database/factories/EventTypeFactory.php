<?php

namespace Database\Factories;

use App\Models\EventType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventType>
 */
class EventTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = EventType::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(1, true), // e.g., "Wedding"
            'description' => $this->faker->sentence(),
        ];
    }
}
