<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Services\PaymentService;
use Illuminate\Console\Command;

class UpdatePaymentNoticesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:update-notices 
                            {--customer= : Update notices for a specific customer ID}
                            {--all : Update notices for all customers}
                            {--force : Force update without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update payment notices based on current installation dates';

    /**
     * Execute the console command.
     */
    public function handle(PaymentService $paymentService)
    {
        $customerId = $this->option('customer');
        $all = $this->option('all');
        $force = $this->option('force');

        if (!$customerId && !$all) {
            $this->error('Please specify either --customer=ID or --all option.');
            return Command::FAILURE;
        }

        if ($customerId) {
            return $this->updateSingleCustomer($customerId, $paymentService, $force);
        }

        if ($all) {
            return $this->updateAllCustomers($paymentService, $force);
        }

        return Command::SUCCESS;
    }

    /**
     * Update payment notices for a single customer.
     */
    private function updateSingleCustomer(int $customerId, PaymentService $paymentService, bool $force): int
    {
        $customer = Customer::find($customerId);

        if (!$customer) {
            $this->error("Customer with ID {$customerId} not found.");
            return Command::FAILURE;
        }

        if (!$customer->plan_installed_at) {
            $this->error("Customer {$customer->full_name} has no installation date set.");
            return Command::FAILURE;
        }

        if (!$force) {
            $confirmed = $this->confirm(
                "This will update payment notices for customer: {$customer->full_name}. Continue?"
            );

            if (!$confirmed) {
                $this->info('Operation cancelled.');
                return Command::SUCCESS;
            }
        }

        $this->info("Updating payment notices for {$customer->full_name}...");
        
        $noticesCreated = $paymentService->updateNoticesForInstallationDateChange($customer);
        
        $this->info("✓ Created {$noticesCreated} payment notices for {$customer->full_name}");
        
        return Command::SUCCESS;
    }

    /**
     * Update payment notices for all customers.
     */
    private function updateAllCustomers(PaymentService $paymentService, bool $force): int
    {
        $customerCount = Customer::whereNotNull('plan_installed_at')
            ->whereHas('plan')
            ->count();

        if ($customerCount === 0) {
            $this->info('No customers with installation dates found.');
            return Command::SUCCESS;
        }

        if (!$force) {
            $confirmed = $this->confirm(
                "This will update payment notices for {$customerCount} customers. Continue?"
            );

            if (!$confirmed) {
                $this->info('Operation cancelled.');
                return Command::SUCCESS;
            }
        }

        $this->info("Updating payment notices for {$customerCount} customers...");
        
        $bar = $this->output->createProgressBar($customerCount);
        $bar->start();

        $results = $paymentService->bulkUpdateNoticesForInstallationDateChanges();
        
        $bar->finish();
        $this->newLine();

        $this->info("✓ Processed {$results['customers_processed']} customers");
        $this->info("✓ Created {$results['total_notices_created']} payment notices");

        if (!empty($results['errors'])) {
            $errorCount = count($results['errors']);
            $this->warn("❌ {$errorCount} errors occurred:");
            foreach ($results['errors'] as $error) {
                $this->error("  - {$error['customer_name']} (ID: {$error['customer_id']}): {$error['error']}");
            }
        }

        return Command::SUCCESS;
    }
}
