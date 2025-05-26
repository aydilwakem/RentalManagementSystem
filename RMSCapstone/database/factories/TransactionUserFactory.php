<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransactionUser>
 */
class TransactionUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'trn_user_type' => $this->faker->randomElement(['tenant', 'guest']),
            'first_name' => $this->faker->firstName(),
            'middle_name' => $this->faker->optional()->firstName(),
            'last_name' => $this->faker->lastName(),
            'suffix' => $this->faker->optional()->randomElement(['Jr.', 'Sr.', 'III']),
            'email' => $this->faker->unique()->safeEmail(),
            'contact_number' => $this->faker->phoneNumber(),
            'company_name' => $this->faker->optional()->company(),
            'house_number' => $this->faker->buildingNumber(),
            'street' => $this->faker->streetName(),
            'barangay' => $this->faker->citySuffix(), // or a more specific value if needed
            'city_municipality' => $this->faker->city(),
            'province' => $this->faker->state(),
            'region' => $this->faker->stateAbbr(),
            'postal_code' => $this->faker->postcode(),
            'country' => $this->faker->country(),
        ];
    }
}
