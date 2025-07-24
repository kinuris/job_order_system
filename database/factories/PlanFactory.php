<?php

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plan>
 */
class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(array_keys(Plan::TYPES));
        
        return [
            'name' => $this->generatePlanName($type),
            'type' => $type,
            'description' => fake()->sentence(10),
            'monthly_rate' => fake()->randomFloat(2, 19.99, 199.99),
            'speed_mbps' => $type === 'internet' ? fake()->randomElement([25, 50, 100, 200, 500, 1000]) : null,
            'is_active' => fake()->boolean(85), // 85% chance of being active
        ];
    }

    /**
     * Generate a plan name based on type.
     */
    private function generatePlanName(string $type): string
    {
        $names = [
            'internet' => [
                'Basic Internet 25',
                'Fast Internet 100',
                'Ultra Internet 500',
                'Gigabit Internet 1000',
                'Home Internet Plus',
                'Business Internet Pro'
            ],
            'cable' => [
                'Basic Cable TV',
                'Premium Cable Package',
                'Sports & Movies Bundle',
                'Family Entertainment Pack',
                'Ultimate Cable Experience'
            ],
            'phone' => [
                'Home Phone Basic',
                'Unlimited Calling Plan',
                'International Phone Plan',
                'Business Phone Service'
            ],
            'bundle' => [
                'Triple Play Bundle',
                'Internet + TV Bundle',
                'Complete Home Package',
                'Business Essentials Bundle',
                'Ultimate Entertainment Bundle'
            ]
        ];

        return fake()->randomElement($names[$type] ?? ['Standard Plan']);
    }

    /**
     * Create an active plan.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Create an inactive plan.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Create an internet plan.
     */
    public function internet(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'internet',
            'name' => fake()->randomElement(['Basic Internet', 'Fast Internet', 'Ultra Internet', 'Gigabit Internet']),
            'speed_mbps' => fake()->randomElement([25, 50, 100, 200, 500, 1000]),
        ]);
    }
}
