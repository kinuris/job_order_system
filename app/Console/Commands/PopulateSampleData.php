<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Customer;
use App\Models\JobOrder;
use App\Models\Technician;
use Illuminate\Console\Command;

class PopulateSampleData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:populate-sample-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate the system with sample customers and job orders for demonstration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Populating system with sample data...');
        $this->newLine();

        // Create sample customers
        $this->info('Creating sample customers...');
        $customers = [];
        
        $customerData = [
            ['first_name' => 'Alice', 'last_name' => 'Johnson', 'email' => 'alice.johnson@email.com', 'phone_number' => '+1-555-0001', 'service_address' => '123 Oak Street, Springfield, IL 62701'],
            ['first_name' => 'Bob', 'last_name' => 'Smith', 'email' => 'bob.smith@email.com', 'phone_number' => '+1-555-0002', 'service_address' => '456 Pine Avenue, Chicago, IL 60601'],
            ['first_name' => 'Carol', 'last_name' => 'Williams', 'email' => 'carol.williams@email.com', 'phone_number' => '+1-555-0003', 'service_address' => '789 Maple Drive, Peoria, IL 61601'],
            ['first_name' => 'David', 'last_name' => 'Brown', 'email' => 'david.brown@email.com', 'phone_number' => '+1-555-0004', 'service_address' => '321 Elm Boulevard, Rockford, IL 61101'],
            ['first_name' => 'Eva', 'last_name' => 'Davis', 'email' => 'eva.davis@email.com', 'phone_number' => '+1-555-0005', 'service_address' => '654 Cedar Lane, Aurora, IL 60502'],
        ];

        foreach ($customerData as $data) {
            $customer = Customer::firstOrCreate(['email' => $data['email']], $data);
            $customers[] = $customer;
            $this->line("✅ Created customer: {$customer->full_name}");
        }

        // Get existing technicians
        $technicians = Technician::with('user')->get();
        if ($technicians->isEmpty()) {
            $this->warn('No technicians found. Creating a sample technician...');
            $techUser = User::factory()->create([
                'name' => 'Mike Technician',
                'username' => 'mike_tech',
                'role' => 'technician'
            ]);
            $technician = Technician::create([
                'user_id' => $techUser->id,
                'phone_number' => '+1-555-9999'
            ]);
            $technicians = collect([$technician]);
        }

        $this->newLine();
        $this->info('Creating sample job orders...');

        // Create varied job orders
        $jobOrdersData = [
            [
                'type' => 'installation',
                'priority' => 'high',
                'description' => 'Install new fiber optic internet connection and configure Wi-Fi network',
                'status' => 'pending_dispatch',
                'scheduled_at' => now()->addDays(1),
            ],
            [
                'type' => 'repair',
                'priority' => 'urgent',
                'description' => 'Internet connection is completely down, customer cannot work from home',
                'status' => 'scheduled',
                'scheduled_at' => now()->addHours(4),
            ],
            [
                'type' => 'maintenance',
                'priority' => 'medium',
                'description' => 'Regular maintenance check on existing equipment',
                'status' => 'in_progress',
                'scheduled_at' => now()->subHours(2),
                'started_at' => now()->subHours(1),
            ],
            [
                'type' => 'upgrade',
                'priority' => 'low',
                'description' => 'Upgrade customer to higher speed internet package',
                'status' => 'completed',
                'scheduled_at' => now()->subDays(2),
                'started_at' => now()->subDays(2)->addHours(1),
                'completed_at' => now()->subDays(2)->addHours(3),
                'resolution_notes' => 'Successfully upgraded to 1Gbps package. Customer tested speeds and confirmed satisfaction.',
            ],
            [
                'type' => 'disconnection',
                'priority' => 'medium',
                'description' => 'Customer is moving and needs service disconnected',
                'status' => 'scheduled',
                'scheduled_at' => now()->addDays(3),
            ],
            [
                'type' => 'repair',
                'priority' => 'high',
                'description' => 'Intermittent connection issues reported by customer',
                'status' => 'pending_dispatch',
                'scheduled_at' => null,
            ],
            [
                'type' => 'installation',
                'priority' => 'medium',
                'description' => 'New business customer needs complete network setup',
                'status' => 'cancelled',
                'scheduled_at' => now()->subDays(1),
            ],
        ];

        foreach ($jobOrdersData as $index => $jobData) {
            $customer = $customers[$index % count($customers)];
            $technician = $technicians->random();
            
            $jobOrder = JobOrder::create(array_merge($jobData, [
                'customer_id' => $customer->id,
                'technician_id' => $technician->id,
            ]));

            $this->line("✅ Created job order #{$jobOrder->id}: {$jobData['type']} for {$customer->full_name} ({$jobData['status']})");
        }

        $this->newLine();
        $this->info('📊 Sample data population complete!');
        $this->newLine();

        // Show summary
        $totalCustomers = Customer::count();
        $totalJobOrders = JobOrder::count();
        $pendingJobs = JobOrder::where('status', 'pending_dispatch')->count();
        $inProgressJobs = JobOrder::where('status', 'in_progress')->count();
        
        $this->comment('Summary:');
        $this->line("• Total Customers: {$totalCustomers}");
        $this->line("• Total Job Orders: {$totalJobOrders}");
        $this->line("• Pending Jobs: {$pendingJobs}");
        $this->line("• In Progress Jobs: {$inProgressJobs}");
        
        $this->newLine();
        $this->info('🎯 You can now visit the dashboard to see the admin functionality in action!');
        $this->comment('Admin login: username = admin, password = password');
        $this->comment('Dashboard URL: http://localhost:8000/dashboard');

        return Command::SUCCESS;
    }
}
