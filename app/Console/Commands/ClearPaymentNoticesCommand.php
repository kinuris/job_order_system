<?php

namespace App\Console\Commands;

use App\Models\PaymentNotice;
use Illuminate\Console\Command;

class ClearPaymentNoticesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:clear-notices 
                            {--status= : Clear notices with specific status (pending, overdue, paid, cancelled)}
                            {--customer= : Clear notices for a specific customer ID}
                            {--all : Clear all payment notices}
                            {--force : Force clear without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear payment notices from the system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $status = $this->option('status');
        $customerId = $this->option('customer');
        $all = $this->option('all');
        $force = $this->option('force');

        if (!$status && !$customerId && !$all) {
            $this->error('Please specify one of the options: --status, --customer, or --all');
            return Command::FAILURE;
        }

        if ($all) {
            return $this->clearAllNotices($force);
        }

        if ($status) {
            return $this->clearNoticesByStatus($status, $force);
        }

        if ($customerId) {
            return $this->clearNoticesForCustomer($customerId, $force);
        }

        return Command::SUCCESS;
    }

    /**
     * Clear all payment notices.
     */
    private function clearAllNotices(bool $force): int
    {
        $totalCount = PaymentNotice::count();

        if ($totalCount === 0) {
            $this->info('No payment notices found to clear.');
            return Command::SUCCESS;
        }

        if (!$force) {
            $confirmed = $this->confirm(
                "⚠️  This will permanently delete ALL {$totalCount} payment notices. This action cannot be undone. Continue?"
            );

            if (!$confirmed) {
                $this->info('Operation cancelled.');
                return Command::SUCCESS;
            }
        }

        $this->info("Clearing all {$totalCount} payment notices...");
        
        $bar = $this->output->createProgressBar($totalCount);
        $bar->start();

        // Delete in chunks to avoid memory issues
        $deletedCount = 0;
        while (PaymentNotice::exists()) {
            $deleted = PaymentNotice::limit(1000)->delete();
            $deletedCount += $deleted;
            $bar->advance($deleted);
            
            if ($deleted === 0) {
                break; // Safety check
            }
        }

        $bar->finish();
        $this->newLine();

        $this->info("✅ Successfully cleared {$deletedCount} payment notices.");
        
        return Command::SUCCESS;
    }

    /**
     * Clear payment notices by status.
     */
    private function clearNoticesByStatus(string $status, bool $force): int
    {
        $validStatuses = ['pending', 'overdue', 'paid', 'cancelled'];
        
        if (!in_array($status, $validStatuses)) {
            $this->error("Invalid status '{$status}'. Valid statuses are: " . implode(', ', $validStatuses));
            return Command::FAILURE;
        }

        $count = PaymentNotice::where('status', $status)->count();

        if ($count === 0) {
            $this->info("No payment notices found with status '{$status}'.");
            return Command::SUCCESS;
        }

        if (!$force) {
            $confirmed = $this->confirm(
                "This will delete {$count} payment notices with status '{$status}'. Continue?"
            );

            if (!$confirmed) {
                $this->info('Operation cancelled.');
                return Command::SUCCESS;
            }
        }

        $this->info("Clearing {$count} payment notices with status '{$status}'...");
        
        $deletedCount = PaymentNotice::where('status', $status)->delete();
        
        $this->info("✅ Successfully cleared {$deletedCount} payment notices with status '{$status}'.");
        
        return Command::SUCCESS;
    }

    /**
     * Clear payment notices for a specific customer.
     */
    private function clearNoticesForCustomer(int $customerId, bool $force): int
    {
        $customer = \App\Models\Customer::find($customerId);

        if (!$customer) {
            $this->error("Customer with ID {$customerId} not found.");
            return Command::FAILURE;
        }

        $count = $customer->paymentNotices()->count();

        if ($count === 0) {
            $this->info("No payment notices found for customer: {$customer->full_name}");
            return Command::SUCCESS;
        }

        if (!$force) {
            $confirmed = $this->confirm(
                "This will delete {$count} payment notices for customer: {$customer->full_name}. Continue?"
            );

            if (!$confirmed) {
                $this->info('Operation cancelled.');
                return Command::SUCCESS;
            }
        }

        $this->info("Clearing {$count} payment notices for {$customer->full_name}...");
        
        $deletedCount = $customer->paymentNotices()->delete();
        
        $this->info("✅ Successfully cleared {$deletedCount} payment notices for {$customer->full_name}.");
        
        return Command::SUCCESS;
    }
}
