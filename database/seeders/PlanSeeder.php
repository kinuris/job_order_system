<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create predefined plans
        $plans = [
            [
                'name' => 'Basic Internet 25',
                'type' => 'internet',
                'description' => 'Perfect for light browsing and email',
                'monthly_rate' => 1299.00,
                'speed_mbps' => 25,
                'is_active' => true,
            ],
            [
                'name' => 'Fast Internet 100',
                'type' => 'internet',
                'description' => 'Great for streaming and video calls',
                'monthly_rate' => 1999.00,
                'speed_mbps' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Ultra Internet 500',
                'type' => 'internet',
                'description' => 'Perfect for gaming and 4K streaming',
                'monthly_rate' => 3499.00,
                'speed_mbps' => 500,
                'is_active' => true,
            ],
            [
                'name' => 'Gigabit Internet 1000',
                'type' => 'internet',
                'description' => 'Ultimate speed for power users',
                'monthly_rate' => 4999.00,
                'speed_mbps' => 1000,
                'is_active' => true,
            ],
            [
                'name' => 'Premium Cable Package',
                'type' => 'cable',
                'description' => 'Over 200 channels including premium movie channels',
                'monthly_rate' => 2999.00,
                'speed_mbps' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Home Phone Unlimited',
                'type' => 'phone',
                'description' => 'Unlimited local and long-distance calling',
                'monthly_rate' => 899.00,
                'speed_mbps' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Triple Play Bundle',
                'type' => 'bundle',
                'description' => 'Internet 100 + Cable + Phone bundle deal',
                'monthly_rate' => 4999.00,
                'speed_mbps' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Basic Cable',
                'type' => 'cable',
                'description' => 'Essential cable channels',
                'monthly_rate' => 1599.00,
                'speed_mbps' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Student Internet 50',
                'type' => 'internet',
                'description' => 'Affordable internet for students',
                'monthly_rate' => 1599.00,
                'speed_mbps' => 50,
                'is_active' => true,
            ],
            [
                'name' => 'Business Internet 200',
                'type' => 'internet',
                'description' => 'Reliable internet for small businesses',
                'monthly_rate' => 2999.00,
                'speed_mbps' => 200,
                'is_active' => false, // Inactive plan
            ],
        ];

        foreach ($plans as $planData) {
            Plan::firstOrCreate(
                ['name' => $planData['name']],
                $planData
            );
        }

        $this->command->info('Plans seeded successfully!');
    }
}
