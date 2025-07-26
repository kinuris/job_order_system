<?php

namespace App\Console\Commands;

use App\Services\PaymentService;
use App\Models\PaymentNotice;
use App\Models\CustomerPayment;
use Illuminate\Console\Command;

class RecalculatePaymentNotices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:recalculate {--clear-payments : Also clear existing customer payments}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate all payment notices from customer installation dates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting payment notice recalculation...');

        // Clear existing payment notices
        $this->info('Clearing existing payment notices...');
        PaymentNotice::query()->delete();

        // Optionally clear customer payments
        if ($this->option('clear-payments')) {
            $this->info('Clearing existing customer payments...');
            CustomerPayment::query()->delete();
        }

        // Recalculate from installation dates
        $paymentService = new PaymentService();
        $totalNotices = $paymentService->recalculateFromInstallationDates();

        $this->info("Generated {$totalNotices} payment notices");

        // Show summary statistics
        $stats = $paymentService->getDashboardStats();
        $pendingCount = PaymentNotice::where('status', 'pending')->count();
        $overdueCount = PaymentNotice::where('status', 'overdue')->count();

        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Notices Generated', number_format($totalNotices)],
                ['Pending Notices', number_format($pendingCount)],
                ['Overdue Notices', number_format($overdueCount)],
                ['Total Unpaid Amount', '₱' . number_format($stats['total_unpaid'], 2)],
                ['Due This Week', number_format($stats['due_this_week'])],
            ]
        );

        $this->info('Payment notice recalculation completed successfully!');

        return Command::SUCCESS;
    }
}
