<?php

namespace Database\Factories;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
{
    protected $model = Activity::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'name' => $this->faker->unique()->words(3, true), // Ensures uniqueness and max:255
        'description' => $this->faker->paragraph(),
        'amount' => $this->faker->randomFloat(2, 0, 5000), // min:100
        'inclusions' => $this->faker->optional()->sentence(),
        ];
    }
}

