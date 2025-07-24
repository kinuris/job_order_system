<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use App\Models\Plan;
use Carbon\Carbon;

class ImportCustomersFromCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:customers {file=import.customer.csv}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import customers from CSV file';

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

        $this->info("Importing customers from: {$filePath}");
        
        $handle = fopen($filePath, 'r');
        $header = fgetcsv($handle); // Skip header row
        
        $imported = 0;
        $skipped = 0;
        $errors = 0;

        while (($row = fgetcsv($handle)) !== false) {
            try {
                // Skip empty rows
                if (empty(array_filter($row))) {
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
                        $this->warn("Plan not found: {$planName} for customer: {$name}");
                    }
                }

                // Parse installation date
                $planInstalledAt = null;
                if (!empty($dateInstalled) && $plan) {
                    try {
                        // Handle various date formats from CSV
                        $planInstalledAt = $this->parseDate($dateInstalled);
                    } catch (\Exception $e) {
                        $this->warn("Could not parse date '{$dateInstalled}' for customer: {$name}");
                    }
                }

                // Check if customer already exists
                $existingCustomer = Customer::where('first_name', $firstName)
                    ->where('last_name', $lastName)
                    ->where('service_address', $address)
                    ->first();

                if ($existingCustomer) {
                    $this->info("Customer already exists: {$name}");
                    $skipped++;
                    continue;
                }

                // Create customer
                $customer = Customer::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'service_address' => $address ?: 'Address not provided',
                    'plan_id' => $plan?->id,
                    'plan_installed_at' => $planInstalledAt,
                    'plan_status' => $plan ? 'active' : null,
                ]);

                $imported++;
                $this->info("Imported: {$name}" . ($plan ? " with plan: {$planName}" : " (no plan)"));

            } catch (\Exception $e) {
                $errors++;
                $this->error("Error importing row: " . implode(',', $row) . " - " . $e->getMessage());
            }
        }

        fclose($handle);

        $this->info("\n=== Import Summary ===");
        $this->info("Imported: {$imported} customers");
        $this->info("Skipped: {$skipped} customers");
        $this->info("Errors: {$errors} customers");

        return 0;
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
