<?php

namespace Database\Factories;

use App\Models\EventType;
use App\Models\ReservationType;
use App\Models\Transaction;
use App\Models\TransactionUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reservation_type_id' => ReservationType::factory(),
            'created_by' => TransactionUser::factory(),
            //'event_type_id' => EventType::factory(),
            'total_adults' => $this->faker->numberBetween(1, 10),
            'total_kids' => $this->faker->numberBetween(0, 5),
            'pax' => $this->faker->numberBetween(1, 15),
            'total_amount' => $this->faker->randomFloat(2, 100, 1000),
            'deposit_amount' => $this->faker->randomFloat(2, 10, 500),
            'terms' => 1,
            'heard_from' => $this->faker->randomElement(['Facebook', 'Instagram', 'TikTok', 'YouTube', 'Google']),
            'reservation_source' => $this->faker->randomElement(['Airbnb', 'WebApp', 'Phone', 'Messenger', 'Other']),
            'transaction_status' => $this->faker->randomElement(['pending', 'reserved', 'receipt_verified', 'confirmed', 'ongoing', 'done', 'no_show', 'terminated', 'expired', 'cancelled']),
            'actual_start_datetime' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'actual_end_datetime' => $this->faker->dateTimeBetween('now', '+1 week'),
            'start_datetime' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'end_datetime' => $this->faker->dateTimeBetween('now', '+1 week'),
        ];
    }
}
