<?php

namespace Database\Factories;

use App\Models\Maintenance;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Maintenance>
 */
class MaintenanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Maintenance::class;
    public function definition(): array
    {
       $reportedAt = $this->faker->dateTimeBetween('now', '+3 days');
        $priorityStatus = $this->faker->randomElement(['emergency', 'urgent', 'routine', 'planned']);

    return [
        'name' => $this->faker->unique()->words(3, true),
        'description' => $this->faker->optional()->sentence(),
        'property_id' => Property::factory(),
        'reported_at' => $reportedAt->format('Y-m-d'),
        'resolved_at' => $this->faker->boolean(70)
            ? $this->faker->dateTimeBetween($reportedAt, '+5 days')->format('Y-m-d')
            : null,
        'planned_datetime' => $priorityStatus === 'planned'
            ? $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d H:i:s')
            : null,
        'priority_status' => $priorityStatus,
    ];
    }
}
