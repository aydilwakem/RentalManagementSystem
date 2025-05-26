<?php

namespace Database\Factories;

use App\Models\PropertyCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PropertyCategory>
 */
class PropertyCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = PropertyCategory::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true), // e.g., "Cozy Rooms"
            'description' => $this->faker->sentence(),
        ];
    }
}
