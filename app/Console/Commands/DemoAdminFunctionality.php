<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Customer;
use App\Models\JobOrder;
use App\Models\Technician;
use Illuminate\Console\Command;

class DemoAdminFunctionality extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Demonstrate admin functionality for creating customers and job orders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Demonstrating Admin Job Order System Functionality');
        $this->newLine();

        // Create an admin user
        $admin = User::factory()->admin()->create([
            'name' => 'Demo Admin',
            'username' => 'demo_admin_' . time(),
        ]);

        $this->info("✅ Created admin user: {$admin->name} (username: {$admin->username})");
        $this->info("   Is admin: " . ($admin->isAdmin() ? 'Yes' : 'No'));
        $this->newLine();

        // Create a customer
        $customer = Customer::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe.' . time() . '@example.com',
            'phone_number' => '+1-555-123-4567',
            'service_address' => '123 Main Street, Anytown, ST 12345'
        ]);

        $this->info("✅ Created customer: {$customer->full_name}");
        $this->info("   Email: {$customer->email}");
        $this->info("   Address: {$customer->service_address}");
        $this->newLine();

        // Create a technician
        $techUser = User::factory()->create([
            'name' => 'Jane Technician',
            'username' => 'jane_tech_' . time(),
            'role' => 'technician'
        ]);

        $technician = Technician::create([
            'user_id' => $techUser->id,
            'phone_number' => '+1-555-987-6543'
        ]);

        $this->info("✅ Created technician: {$technician->full_name}");
        $this->info("   Phone: {$technician->phone_number}");
        $this->newLine();

        // Create a job order
        $jobOrder = JobOrder::create([
            'customer_id' => $customer->id,
            'technician_id' => $technician->id,
            'type' => 'installation',
            'priority' => 'high',
            'description' => 'Install new internet connection and set up Wi-Fi router',
            'status' => 'pending_dispatch',
            'scheduled_at' => now()->addDays(2),
        ]);

        $this->info("✅ Created job order #: {$jobOrder->id}");
        $this->info("   Type: " . JobOrder::TYPES[$jobOrder->type]);
        $this->info("   Priority: " . JobOrder::PRIORITIES[$jobOrder->priority]);
        $this->info("   Status: " . JobOrder::STATUSES[$jobOrder->status]);
        $this->info("   Description: {$jobOrder->description}");
        $this->info("   Scheduled for: {$jobOrder->scheduled_at}");
        $this->newLine();

        // Show relationships work
        $this->info("📊 Relationship Verification:");
        $this->info("   Customer's job orders count: " . $customer->jobOrders()->count());
        $this->info("   Technician's job orders count: " . $technician->jobOrders()->count());
        $this->info("   Job order customer: {$jobOrder->customer->full_name}");
        $this->info("   Job order technician: {$jobOrder->technician->full_name}");
        $this->newLine();

        $this->info("🎉 Admin functionality demonstration complete!");
        $this->newLine();
        
        $this->comment('Summary of what admins can do:');
        $this->line('✅ Create and manage customers');
        $this->line('✅ Create and manage job orders');
        $this->line('✅ Assign technicians to job orders');
        $this->line('✅ Update job order status and priority');
        $this->line('✅ View all customers and job orders');
        $this->line('✅ Access is protected by role-based middleware');
        
        $this->newLine();
        $this->comment('Available admin routes:');
        $this->line('• GET    /admin - Dashboard');
        $this->line('• GET    /admin/customers - List customers');
        $this->line('• POST   /admin/customers - Create customer');
        $this->line('• GET    /admin/customers/{id} - View customer');
        $this->line('• PUT    /admin/customers/{id} - Update customer');
        $this->line('• DELETE /admin/customers/{id} - Delete customer');
        $this->line('• GET    /admin/job-orders - List job orders');
        $this->line('• POST   /admin/job-orders - Create job order');
        $this->line('• GET    /admin/job-orders/{id} - View job order');
        $this->line('• PUT    /admin/job-orders/{id} - Update job order');
        $this->line('• PATCH  /admin/job-orders/{id}/assign-technician - Assign technician');
        $this->line('• PATCH  /admin/job-orders/{id}/update-status - Update status');

        return Command::SUCCESS;
    }
}
