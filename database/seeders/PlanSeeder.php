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
        // Create predefined plans based on import.customer.csv data
        $plans = [
            // Internet plans based on CSV data
            [
                'name' => 'HP 5mbps',
                'type' => 'internet',
                'description' => 'Basic internet plan with 5 Mbps speed',
                'monthly_rate' => 1299.00, // Placeholder price
                'speed_mbps' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'HP 10mbps',
                'type' => 'internet',
                'description' => 'Standard internet plan with 10 Mbps speed',
                'monthly_rate' => 1899.00, // Placeholder price
                'speed_mbps' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'HP 15mbps',
                'type' => 'internet',
                'description' => 'Premium internet plan with 15 Mbps speed',
                'monthly_rate' => 2499.00, // Placeholder price
                'speed_mbps' => 15,
                'is_active' => true,
            ],
            [
                'name' => 'HP 8mbps',
                'type' => 'internet',
                'description' => 'Mid-tier internet plan with 8 Mbps speed',
                'monthly_rate' => 1699.00, // Placeholder price
                'speed_mbps' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'HP 5mb',
                'type' => 'internet',
                'description' => 'Basic internet plan (alternative naming)',
                'monthly_rate' => 1299.00, // Placeholder price
                'speed_mbps' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'HP',
                'type' => 'internet',
                'description' => 'Standard HP internet plan',
                'monthly_rate' => 1499.00, // Placeholder price
                'speed_mbps' => null, // Speed not specified
                'is_active' => true,
            ],
            
            // Additional standard plans for system completeness
            [
                'name' => 'Basic Internet 25',
                'type' => 'internet',
                'description' => 'Fast internet for streaming and browsing',
                'monthly_rate' => 2999.00,
                'speed_mbps' => 25,
                'is_active' => true,
            ],
            [
                'name' => 'Premium Internet 50',
                'type' => 'internet',
                'description' => 'High-speed internet for multiple devices',
                'monthly_rate' => 3999.00,
                'speed_mbps' => 50,
                'is_active' => true,
            ],
            [
                'name' => 'Ultra Internet 100',
                'type' => 'internet',
                'description' => 'Ultra-fast internet for heavy usage',
                'monthly_rate' => 5999.00,
                'speed_mbps' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Premium Cable Package',
                'type' => 'cable',
                'description' => 'Complete cable TV package with premium channels',
                'monthly_rate' => 2999.00,
                'speed_mbps' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Basic Cable',
                'type' => 'cable',
                'description' => 'Essential cable TV channels',
                'monthly_rate' => 1599.00,
                'speed_mbps' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Home Phone Service',
                'type' => 'phone',
                'description' => 'Unlimited local and long-distance calling',
                'monthly_rate' => 899.00,
                'speed_mbps' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Triple Play Bundle',
                'type' => 'bundle',
                'description' => 'Internet + Cable + Phone bundle package',
                'monthly_rate' => 4999.00,
                'speed_mbps' => 25,
                'is_active' => true,
            ],
        ];

        foreach ($plans as $planData) {
            Plan::firstOrCreate(
                ['name' => $planData['name']],
                $planData
            );
        }

        $this->command->info('Plans seeded successfully with CSV-based plan types!');
    }
}
