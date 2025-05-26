<?php

namespace Database\Factories;

use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentMethod>
 */
class PaymentMethodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = PaymentMethod::class;
    public function definition(): array
    {
        return [
            'mode_of_payment_name'   => $this->faker->words(2, true), // e.g., "Bank Transfer"
            'account_name'           => $this->faker->name(),
            'account_number'         => $this->faker->numerify('##########'), // e.g., "1234567890"
            //'mode_of_payment_qr_image' => 'qr_images/' . $this->faker->uuid() . '.png',
        ];
    }
}
