<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Technician;
use App\Models\JobOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobOrder>
 */
class JobOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'technician_id' => Technician::factory(),
            'type' => fake()->randomElement(array_keys(JobOrder::TYPES)),
            'status' => fake()->randomElement(array_keys(JobOrder::STATUSES)),
            'priority' => fake()->randomElement(array_keys(JobOrder::PRIORITIES)),
            'description' => fake()->paragraph(),
            'resolution_notes' => fake()->optional()->paragraph(),
            'scheduled_at' => fake()->optional()->dateTimeBetween('now', '+1 month'),
            'started_at' => fake()->optional()->dateTimeBetween('-1 week', 'now'),
            'completed_at' => fake()->optional()->dateTimeBetween('-1 week', 'now'),
        ];
    }

    /**
     * Create a pending job order.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending_dispatch',
            'started_at' => null,
            'completed_at' => null,
        ]);
    }

    /**
     * Create a completed job order.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'started_at' => fake()->dateTimeBetween('-1 week', '-1 day'),
            'completed_at' => fake()->dateTimeBetween('-1 day', 'now'),
            'resolution_notes' => fake()->paragraph(),
        ]);
    }

    /**
     * Create an in-progress job order.
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_progress',
            'started_at' => fake()->dateTimeBetween('-1 day', 'now'),
            'completed_at' => null,
        ]);
    }
}
