<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Payment::class;
    public function definition(): array
    {
        return [
            'invoice_id' => Invoice::factory(),
            'payment_method_id' => PaymentMethod::factory(),
            'amount_paid' => $this->faker->randomFloat(2, 10, 5000),
            'payment_type' => $this->faker->randomElement(['Room Rent', 'House Rent', 'Activity Fee', 'Event Hall', 'Event Package', 'Security Deposit']),
            'payment_screenshot' => null, // or use fake()->imageUrl() if applicable
            'payment_reference_number' => $this->faker->unique()->uuid,
            'payment_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'payment_status' => $this->faker->randomElement(['pending', 'completed', 'failed']),
            'rejection_reason' => null, // set conditionally if needed
            'notes' => $this->faker->optional()->sentence,
            'verified_at' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'),
            'currency' => $this->faker->currencyCode,
        ];
    }
}
