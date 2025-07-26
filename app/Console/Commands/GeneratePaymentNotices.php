<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PaymentService;

class GeneratePaymentNotices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:generate-notices 
                            {--update-overdue : Also update overdue statuses}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate monthly payment notices for active customers';

    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        parent::__construct();
        $this->paymentService = $paymentService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting payment notice generation...');
        
        // Update overdue statuses first if requested
        if ($this->option('update-overdue')) {
            $this->info('Updating overdue statuses...');
            $overdueCount = $this->paymentService->updateOverdueStatuses();
            $this->info("Updated {$overdueCount} overdue notices.");
        }
        
        // Generate new notices
        $this->info('Generating new payment notices...');
        $noticesCount = $this->paymentService->generateMonthlyNotices();
        
        if ($noticesCount > 0) {
            $this->info("Successfully generated {$noticesCount} payment notices!");
        } else {
            $this->info('No new payment notices needed at this time.');
        }
        
        // Show summary
        $stats = $this->paymentService->getDashboardStats();
        
        $this->newLine();
        $this->info('Payment Summary:');
        $this->line("- Overdue notices: {$stats['overdue_notices']}");
        $this->line("- Due this week: {$stats['due_this_week']}");
        $this->line("- Total unpaid: ₱" . number_format($stats['total_unpaid'], 2));
        
        return Command::SUCCESS;
    }
}
