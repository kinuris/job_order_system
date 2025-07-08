<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Customer;
use App\Models\JobOrder;
use App\Models\Technician;
use Illuminate\Console\Command;

class DemoAdminWorkflow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:demo-workflow';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Demonstrate the complete admin workflow for creating customers and job orders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🎯 Admin Workflow Demonstration');
        $this->comment('This demonstrates what an admin can do in the Job Order Management System');
        $this->newLine();

        // Step 1: Admin creates a customer
        $this->info('Step 1: Admin creates a new customer');
        $customer = Customer::create([
            'first_name' => 'Emma',
            'last_name' => 'Wilson',
            'email' => 'emma.wilson@example.com',
            'phone_number' => '+1-555-1234',
            'service_address' => '999 Demo Street, Example City, ST 12345'
        ]);
        $this->line("✅ Created customer: {$customer->full_name}");
        $this->line("   Email: {$customer->email}");
        $this->line("   Address: {$customer->service_address}");
        $this->newLine();

        // Step 2: Admin creates a job order for the customer
        $this->info('Step 2: Admin creates a job order for the customer');
        $technician = Technician::first();
        
        $jobOrder = JobOrder::create([
            'customer_id' => $customer->id,
            'technician_id' => $technician ? $technician->id : null,
            'type' => 'installation',
            'priority' => 'high',
            'description' => 'Customer needs new high-speed internet installation with Wi-Fi setup',
            'status' => 'pending_dispatch',
            'scheduled_at' => now()->addDays(1),
        ]);
        
        $this->line("✅ Created job order #{$jobOrder->id}");
        $this->line("   Type: " . JobOrder::TYPES[$jobOrder->type]);
        $this->line("   Priority: " . JobOrder::PRIORITIES[$jobOrder->priority]);
        $this->line("   Status: " . JobOrder::STATUSES[$jobOrder->status]);
        $this->line("   Scheduled: {$jobOrder->scheduled_at}");
        if ($technician) {
            $this->line("   Assigned to: {$technician->full_name}");
        }
        $this->newLine();

        // Step 3: Admin updates job order status
        $this->info('Step 3: Admin updates job order status to "scheduled"');
        $jobOrder->update(['status' => 'scheduled']);
        $this->line("✅ Job order status updated to: " . JobOrder::STATUSES[$jobOrder->status]);
        $this->newLine();

        // Step 4: Show current system stats
        $this->info('Step 4: Current system overview');
        $stats = [
            'total_customers' => Customer::count(),
            'total_job_orders' => JobOrder::count(),
            'pending_jobs' => JobOrder::where('status', 'pending_dispatch')->count(),
            'scheduled_jobs' => JobOrder::where('status', 'scheduled')->count(),
            'in_progress_jobs' => JobOrder::where('status', 'in_progress')->count(),
            'completed_jobs' => JobOrder::where('status', 'completed')->count(),
        ];

        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Customers', $stats['total_customers']],
                ['Total Job Orders', $stats['total_job_orders']],
                ['Pending Jobs', $stats['pending_jobs']],
                ['Scheduled Jobs', $stats['scheduled_jobs']],
                ['In Progress Jobs', $stats['in_progress_jobs']],
                ['Completed Jobs', $stats['completed_jobs']],
            ]
        );

        $this->newLine();
        $this->info('🎉 Admin workflow demonstration complete!');
        $this->newLine();

        $this->comment('What admins can do:');
        $this->line('• ✅ Create and manage customers');
        $this->line('• ✅ Create and manage job orders');
        $this->line('• ✅ Assign technicians to jobs');
        $this->line('• ✅ Update job status and priority');
        $this->line('• ✅ View comprehensive dashboard with statistics');
        $this->line('• ✅ Search and filter customers and job orders');
        $this->line('• ✅ Role-based access control ensures security');

        $this->newLine();
        $this->info('🌐 Visit the dashboard to see this in action:');
        $this->comment('URL: http://localhost:8000/dashboard');
        $this->comment('Admin Login: username = admin, password = password');

        return Command::SUCCESS;
    }
}
