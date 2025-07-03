<?php

namespace Database\Seeders;

use App\Models\Technician;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create an admin user
        User::factory()->admin()->create([
            'name' => 'Admin User',
            'username' => 'admin',
            'password' => bcrypt('password'),
        ]);

        // Create two technician users
        $tech1 = User::factory()->create([
            'name' => 'Technician One',
            'username' => 'tech1',
            'password' => bcrypt('password'),
        ]);

        $tech2 = User::factory()->create([
            'name' => 'Technician Two',
            'username' => 'tech2',
            'password' => bcrypt('password'),
        ]);

        // Create technician profiles for the technician users
        Technician::factory()->create([
            'user_id' => $tech1->id,
            'phone_number' => '555-1234',
        ]);

        Technician::factory()->create([
            'user_id' => $tech2->id,
            'phone_number' => '555-5678',
        ]);
    }
}
