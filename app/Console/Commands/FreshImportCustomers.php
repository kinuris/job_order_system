<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use App\Models\JobOrder;
use App\Models\JobOrderMessage;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FreshImportCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:fresh-customers {file=import.customer.csv} {--force : Force deletion without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear existing customers and job orders, then reimport customers from CSV';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = base_path($this->argument('file'));
        
        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        // Warning about data deletion
        $this->warn('⚠️  WARNING: This command will DELETE ALL existing customers and job orders!');
        $this->warn('This action cannot be undone.');
        
        if (!$this->option('force')) {
            if (!$this->confirm('Are you sure you want to proceed?')) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        $this->info('Starting fresh import process...');

        // Step 1: Clear existing data
        $this->clearExistingData();

        // Step 2: Import customers from CSV
        $this->importCustomersFromCsv($filePath);

        $this->info('✅ Fresh import completed successfully!');
        return 0;
    }

    /**
     * Clear existing customer and job order data
     */
    private function clearExistingData()
    {
        $this->info('🗑️  Clearing existing data...');

        // Get counts before deletion for reporting
        $customerCount = Customer::count();
        $jobOrderCount = JobOrder::count();
        $messageCount = JobOrderMessage::count();

        $this->info("Deleting {$messageCount} job order messages...");
        JobOrderMessage::query()->delete();

        $this->info("Deleting {$jobOrderCount} job orders...");
        JobOrder::query()->delete();

        $this->info("Deleting {$customerCount} customers...");
        Customer::query()->delete();

        $this->info('✅ Data cleared successfully.');
    }

    /**
     * Import customers from CSV file
     */
    private function importCustomersFromCsv($filePath)
    {
        $this->info("📥 Importing customers from: {$filePath}");
        
        $handle = fopen($filePath, 'r');
        $header = fgetcsv($handle); // Skip header row
        
        $imported = 0;
        $skipped = 0;
        $errors = 0;

        $progressBar = $this->output->createProgressBar();
        $progressBar->start();

        while (($row = fgetcsv($handle)) !== false) {
            $progressBar->advance();

            try {
                // Skip empty rows
                if (empty(array_filter($row))) {
                    $skipped++;
                    continue;
                }

                $name = trim($row[0] ?? '');
                $address = trim($row[1] ?? '');
                $planName = trim($row[2] ?? '');
                $dateInstalled = trim($row[3] ?? '');

                // Skip if no name
                if (empty($name)) {
                    $skipped++;
                    continue;
                }

                // Parse name into first and last name
                $nameParts = explode(' ', $name, 2);
                $firstName = $nameParts[0];
                $lastName = $nameParts[1] ?? '';

                // Find the plan if specified
                $plan = null;
                if (!empty($planName)) {
                    $plan = Plan::where('name', $planName)->first();
                    if (!$plan) {
                        $this->newLine();
                        $this->warn("Plan not found: '{$planName}' for customer: {$name}");
                        $this->info("Available plans: " . Plan::pluck('name')->implode(', '));
                    }
                }

                // Parse installation date
                $planInstalledAt = null;
                if (!empty($dateInstalled) && $plan) {
                    try {
                        $planInstalledAt = $this->parseDate($dateInstalled);
                    } catch (\Exception $e) {
                        $this->newLine();
                        $this->warn("Could not parse date '{$dateInstalled}' for customer: {$name}");
                    }
                }

                // Create customer with proper email generation
                $email = $this->generateUniqueEmail($firstName, $lastName);

                $customer = Customer::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'phone_number' => $this->generatePhoneNumber(),
                    'service_address' => $address ?: 'Address not provided',
                    'plan_id' => $plan?->id,
                    'plan_installed_at' => $planInstalledAt,
                    'plan_status' => $plan ? 'active' : null,
                ]);

                $imported++;

            } catch (\Exception $e) {
                $errors++;
                $this->newLine();
                $this->error("Error importing row: " . implode(',', $row) . " - " . $e->getMessage());
            }
        }

        $progressBar->finish();
        $this->newLine();

        fclose($handle);

        $this->info("\n📊 Import Summary:");
        $this->table(
            ['Metric', 'Count'],
            [
                ['Imported', $imported],
                ['Skipped', $skipped],
                ['Errors', $errors],
                ['Total Processed', $imported + $skipped + $errors],
            ]
        );

        // Show plan distribution
        $this->showPlanDistribution();
    }

    /**
     * Show distribution of customers by plan
     */
    private function showPlanDistribution()
    {
        $this->info("\n📈 Plan Distribution:");
        
        $planStats = Customer::whereNotNull('plan_id')
            ->with('plan')
            ->get()
            ->groupBy('plan.name')
            ->map(function ($customers) {
                return $customers->count();
            })
            ->sortDesc();

        $customersWithoutPlan = Customer::whereNull('plan_id')->count();

        $tableData = [];
        foreach ($planStats as $planName => $count) {
            $tableData[] = [$planName, $count];
        }
        
        if ($customersWithoutPlan > 0) {
            $tableData[] = ['[No Plan]', $customersWithoutPlan];
        }

        $this->table(['Plan Name', 'Customer Count'], $tableData);
        
        $totalCustomers = Customer::count();
        $this->info("Total customers: {$totalCustomers}");
    }

    /**
     * Generate a unique email address for the customer
     */
    private function generateUniqueEmail($firstName, $lastName)
    {
        $baseEmail = strtolower($firstName);
        if ($lastName) {
            $baseEmail .= '.' . strtolower(str_replace(' ', '', $lastName));
        }
        
        // Remove special characters and spaces
        $baseEmail = preg_replace('/[^a-z0-9.]/', '', $baseEmail);
        
        $email = $baseEmail . '@example.com';
        
        // Check if email already exists and make it unique
        $counter = 1;
        while (Customer::where('email', $email)->exists()) {
            $email = $baseEmail . $counter . '@example.com';
            $counter++;
        }
        
        return $email;
    }

    /**
     * Generate a simple email address for the customer (legacy method)
     */
    private function generateEmail($firstName, $lastName)
    {
        return $this->generateUniqueEmail($firstName, $lastName);
    }

    /**
     * Generate a placeholder phone number
     */
    private function generatePhoneNumber()
    {
        return '+63' . rand(900000000, 999999999);
    }

    /**
     * Parse various date formats from the CSV
     */
    private function parseDate($dateString)
    {
        // Remove quotes and clean up
        $dateString = trim($dateString, '"');
        
        // Try various date formats
        $formats = [
            'M j, Y',      // "July 9, 2025"
            'M. j, Y',     // "Nov. 8, 2024" 
            'M j, Y',      // "Oct 8, 2022"
            'M j, Y',      // "Aug 9, 2020"
            'M. j, Y',     // "Apr. 26, 2022"
            'Y-m-d',       // "2025-07-09"
            'd/m/Y',       // "09/07/2025"
            'm/d/Y',       // "07/09/2025"
        ];

        foreach ($formats as $format) {
            try {
                $date = Carbon::createFromFormat($format, $dateString);
                if ($date) {
                    return $date->format('Y-m-d');
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        // If all else fails, try Carbon's flexible parsing
        try {
            return Carbon::parse($dateString)->format('Y-m-d');
        } catch (\Exception $e) {
            throw new \Exception("Unable to parse date: {$dateString}");
        }
    }
}
