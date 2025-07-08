<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Technician;
use App\Models\Customer;
use App\Models\JobOrder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Clean up existing data first
        $this->cleanupData();

        // Create an admin user
        $adminUser = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'System Administrator',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Create a technician user
        $techUser = User::firstOrCreate(
            ['username' => 'tech1'],
            [
                'name' => 'John Technician',
                'username' => 'tech1',
                'password' => Hash::make('password'),
                'role' => 'technician',
            ]
        );

        // Create technician profile for the technician user
        Technician::firstOrCreate(
            ['user_id' => $techUser->id],
            [
                'user_id' => $techUser->id,
                'phone_number' => '+1234567890',
            ]
        );
    }

    /**
     * Clean up existing customer and job order data.
     */
    private function cleanupData(): void
    {
        // Remove all job orders first (due to foreign key constraints)
        JobOrder::truncate();
        
        // Get all customers
        $customers = Customer::all();
        
        if ($customers->count() > 1) {
            // Keep only the first customer and delete the rest
            $firstCustomer = $customers->first();
            Customer::where('id', '!=', $firstCustomer->id)->delete();
            
            $this->command->info('Removed ' . ($customers->count() - 1) . ' customer records, kept 1.');
        } elseif ($customers->count() === 0) {
            // Create a sample customer if none exist
            Customer::create([
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'phone_number' => '+1234567890',
                'service_address' => '123 Main Street, City, State 12345',
            ]);
            
            $this->command->info('Created 1 sample customer record.');
        } else {
            $this->command->info('Kept existing customer record.');
        }
        
        $this->command->info('Removed all job order records.');
    }
}
