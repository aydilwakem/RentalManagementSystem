<?php

namespace Database\Factories;

use App\Models\PropertyFeature;
use App\Models\PropertyType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PropertyFeature>
 */
class PropertyFeatureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = PropertyFeature::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(), // e.g., "Pool", "Wi-Fi", etc.
            'property_type_id' => PropertyType::factory(),
        ];
    }
}
