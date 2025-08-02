<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\PaymentNotice;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentNotice>
 */
class PaymentNoticeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentNotice::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dueDate = $this->faker->dateTimeBetween('now', '+30 days');
        $periodFrom = (clone $dueDate)->modify('-1 month');
        $periodTo = (clone $dueDate)->modify('-1 day');

        return [
            'customer_id' => Customer::factory(),
            'plan_id' => Plan::factory(),
            'due_date' => $dueDate,
            'period_from' => $periodFrom,
            'period_to' => $periodTo,
            'amount_due' => $this->faker->randomFloat(2, 500, 2000),
            'status' => $this->faker->randomElement(['pending', 'overdue', 'paid']),
            'paid_at' => null,
        ];
    }

    /**
     * Indicate that the payment notice is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'paid_at' => null,
        ]);
    }

    /**
     * Indicate that the payment notice is overdue.
     */
    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'overdue',
            'due_date' => $this->faker->dateTimeBetween('-30 days', '-1 day'),
            'paid_at' => null,
        ]);
    }

    /**
     * Indicate that the payment notice is paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
            'paid_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ]);
    }

    /**
     * Set a specific customer for the payment notice.
     */
    public function forCustomer(Customer $customer): static
    {
        return $this->state(fn (array $attributes) => [
            'customer_id' => $customer->id,
            'plan_id' => $customer->plan_id,
        ]);
    }

    /**
     * Set a specific plan for the payment notice.
     */
    public function forPlan(Plan $plan): static
    {
        return $this->state(fn (array $attributes) => [
            'plan_id' => $plan->id,
            'amount_due' => $plan->monthly_rate,
        ]);
    }
}
