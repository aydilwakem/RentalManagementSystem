<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\PropertyCategory;
use App\Models\PropertyType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Property::class;
    public function definition(): array
    {
        return [
            'property_type_id' => $this->faker->randomElement([1, 2, 3]), // 1-3 only
            'property_category_id' => PropertyCategory::factory(), // optional or required
            'name_number' => $this->faker->bothify('Property-###'),
            'ideal_guest' => $this->faker->optional()->numberBetween(2, 6),
            'capacity' => $this->faker->numberBetween(2, 10),
            'max_adults' => $this->faker->numberBetween(2, 8),
            'max_kids' => $this->faker->numberBetween(0, 5),
            //'occupancy_rules' => ['no smoking', 'no pets', 'quiet after 10pm'], // example default
            'turnover_duration' => $this->faker->numberBetween(1, 10),
            'property_status' => 'available',
            'house_number' => $this->faker->buildingNumber(),
            'street' => $this->faker->streetName(),
            'barangay' => $this->faker->word(),
            'city_municipality' => $this->faker->city(),
            'region' => $this->faker->state(),
            'postal_code' => $this->faker->postcode(),
            'country' => $this->faker->country(),
            'amount' => $this->faker->randomFloat(2, 500, 5000),
            'extra_charge_per_hour' => $this->faker->randomFloat(2, 50, 500),
            'extra_person_charge' => $this->faker->randomFloat(2, 100, 1000),
            'image' => 'property.jpg',
            'images' => ['image1.jpg', 'image2.jpg'],
            'description' => $this->faker->paragraph(),
        ];
    }
}
