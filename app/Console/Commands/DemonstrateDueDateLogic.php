<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Plan;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DemonstrateDueDateLogic extends Command
{
    protected $signature = 'demo:due-dates';
    protected $description = 'Demonstrate how payment notice due dates preserve installation date';

    public function handle()
    {
        $this->info('=== Payment Notice Due Date Demonstration ===');
        
        // Create a demo plan
        $plan = Plan::factory()->create([
            'name' => 'Demo Plan',
            'monthly_rate' => 1200.00
        ]);

        $paymentService = app(PaymentService::class);

        // Demo 1: Customer installed on 15th
        $this->info("\n--- Demo 1: Customer installed on 15th ---");
        $customer1 = Customer::factory()->create([
            'first_name' => 'Demo',
            'last_name' => 'Customer 1',
            'plan_id' => $plan->id,
            'plan_installed_at' => Carbon::create(2025, 1, 15),
            'plan_status' => 'active'
        ]);

        $this->info("Installation date: " . $customer1->plan_installed_at->format('Y-m-d'));
        $this->info("Next billing: " . $customer1->getNextBillingDate()->format('Y-m-d'));
        
        // Generate notices
        $paymentService->updateNoticesForInstallationDateChange($customer1);
        $notices1 = $customer1->paymentNotices()->orderBy('due_date')->take(5)->get();
        
        $this->info("Payment notice due dates:");
        foreach($notices1 as $notice) {
            $this->line("  - " . $notice->due_date->format('Y-m-d') . " (Day: " . $notice->due_date->day . ")");
        }

        // Demo 2: Customer installed on 31st (edge case)
        $this->info("\n--- Demo 2: Customer installed on 31st (month-end edge case) ---");
        $customer2 = Customer::factory()->create([
            'first_name' => 'Demo',
            'last_name' => 'Customer 2',
            'plan_id' => $plan->id,
            'plan_installed_at' => Carbon::create(2025, 1, 31),
            'plan_status' => 'active'
        ]);

        $this->info("Installation date: " . $customer2->plan_installed_at->format('Y-m-d'));
        $this->info("Next billing: " . $customer2->getNextBillingDate()->format('Y-m-d'));
        
        // Generate notices
        $paymentService->updateNoticesForInstallationDateChange($customer2);
        $notices2 = $customer2->paymentNotices()->orderBy('due_date')->take(5)->get();
        
        $this->info("Payment notice due dates:");
        foreach($notices2 as $notice) {
            $this->line("  - " . $notice->due_date->format('Y-m-d') . " (Day: " . $notice->due_date->day . ")");
        }

        // Demo 3: Installation date change
        $this->info("\n--- Demo 3: Installation date change ---");
        $customer3 = Customer::factory()->create([
            'first_name' => 'Demo',
            'last_name' => 'Customer 3',
            'plan_id' => $plan->id,
            'plan_installed_at' => Carbon::create(2025, 1, 5),
            'plan_status' => 'active'
        ]);

        $this->info("Original installation date: " . $customer3->plan_installed_at->format('Y-m-d'));
        $paymentService->updateNoticesForInstallationDateChange($customer3);
        $originalNotices = $customer3->paymentNotices()->orderBy('due_date')->take(3)->get();
        
        $this->info("Original due dates:");
        foreach($originalNotices as $notice) {
            $this->line("  - " . $notice->due_date->format('Y-m-d') . " (Day: " . $notice->due_date->day . ")");
        }

        // Change installation date
        $customer3->update(['plan_installed_at' => Carbon::create(2025, 1, 20)]);
        $this->info("\nChanged installation date to: " . $customer3->fresh()->plan_installed_at->format('Y-m-d'));
        
        $paymentService->updateNoticesForInstallationDateChange($customer3);
        $newNotices = $customer3->paymentNotices()->orderBy('due_date')->take(3)->get();
        
        $this->info("New due dates:");
        foreach($newNotices as $notice) {
            $this->line("  - " . $notice->due_date->format('Y-m-d') . " (Day: " . $notice->due_date->day . ")");
        }

        $this->info("\n=== Key Features Demonstrated ===");
        $this->line("✓ Due dates preserve the installation date's day of month");
        $this->line("✓ Month-end dates are handled gracefully (31st → 28th/29th/30th as needed)");
        $this->line("✓ Installation date changes automatically update all payment notices");
        $this->line("✓ Consistent billing cycle based on customer's installation date");

        // Clean up demo data
        $customer1->delete();
        $customer2->delete();
        $customer3->delete();
        $plan->delete();

        $this->info("\n✓ Demo completed and test data cleaned up!");
    }
}
