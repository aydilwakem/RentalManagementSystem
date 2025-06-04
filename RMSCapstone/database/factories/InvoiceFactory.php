<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Invoice::class; 
    public function definition(): array
    {
        $subTotal = $this->faker->randomFloat(2, 100, 10000);
        $deposit = $this->faker->randomFloat(2, 0, $subTotal);
        $amountPaid = $this->faker->randomFloat(2, $deposit, $subTotal);
        $balanceDue = $subTotal - $amountPaid;

        return [
            'transaction_id' => Transaction::factory(),
            'invoice_number' => 'INV-' . strtoupper(Str::random(8)), 
            'invoice_type' => $this->faker->randomElement(['Room', 'House', 'Event_Hall']),
            'sub_total' => $subTotal,
            'deposit_paid' => $deposit,
            'amount_paid' => $amountPaid,
            'balance_due' => $balanceDue,
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'invoice_status' => $this->faker->randomElement(['pending', 'completed', 'failed', 'overdue']),
            'completed_at' => $this->faker->optional()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
