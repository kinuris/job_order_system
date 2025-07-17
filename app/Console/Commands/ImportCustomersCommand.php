<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportCustomersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:customers {file=import.customer.csv : Path to the CSV file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import customers from CSV file into the customers table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');
        
        // Check if file exists in the project root
        $fullPath = base_path($filePath);
        
        if (!file_exists($fullPath)) {
            $this->error("File not found: {$fullPath}");
            return 1;
        }

        $this->info("Starting customer import from: {$fullPath}");

        try {
            DB::beginTransaction();

            $handle = fopen($fullPath, 'r');
            if (!$handle) {
                $this->error("Could not open file: {$fullPath}");
                return 1;
            }

            // Skip the header row
            $header = fgetcsv($handle);
            $this->info("CSV Header: " . implode(', ', $header));

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
                    // Ignore Plan field (index 2)
                    $dateInstalled = trim($row[3] ?? '');

                    // Skip if name is empty
                    if (empty($name)) {
                        $skipped++;
                        $this->warn("Skipping row with empty name");
                        continue;
                    }

                    // Parse name into first and last name
                    $nameParts = $this->parseName($name);
                    
                    // Generate email and phone if not provided
                    $email = $this->generateEmail($name);
                    $phone = $this->generatePhone();

                    // Check if customer already exists by name and address
                    $existingCustomer = Customer::where('first_name', $nameParts['first_name'])
                        ->where('last_name', $nameParts['last_name'])
                        ->where('service_address', $address)
                        ->first();

                    if ($existingCustomer) {
                        $skipped++;
                        $this->warn("Customer already exists: {$name} at {$address}");
                        continue;
                    }

                    // Create customer
                    Customer::create([
                        'first_name' => $nameParts['first_name'],
                        'last_name' => $nameParts['last_name'],
                        'email' => $email,
                        'phone_number' => $phone,
                        'service_address' => $address ?: 'Address not specified',
                    ]);

                    $imported++;
                    $this->info("Imported: {$name}");

                } catch (\Exception $e) {
                    $errors++;
                    $this->error("Error importing row: " . $e->getMessage());
                    Log::error("Customer import error", [
                        'row' => $row,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            fclose($handle);

            DB::commit();

            $this->info("\n=== Import Summary ===");
            $this->info("Imported: {$imported} customers");
            $this->info("Skipped: {$skipped} customers");
            $this->info("Errors: {$errors} customers");

            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Import failed: " . $e->getMessage());
            Log::error("Customer import failed", ['error' => $e->getMessage()]);
            return 1;
        }
    }

    /**
     * Parse a full name into first and last name parts
     */
    private function parseName(string $name): array
    {
        $parts = explode(' ', trim($name));
        
        if (count($parts) === 1) {
            return [
                'first_name' => $parts[0],
                'last_name' => 'Unknown'
            ];
        }

        $firstName = array_shift($parts);
        $lastName = implode(' ', $parts);

        return [
            'first_name' => $firstName,
            'last_name' => $lastName
        ];
    }

    /**
     * Generate a unique email based on name (or null)
     */
    private function generateEmail(string $name): ?string
    {
        $baseEmail = strtolower(str_replace(' ', '.', $name));
        $baseEmail = preg_replace('/[^a-z0-9.]/', '', $baseEmail);
        
        // Return null if name cleanup resulted in empty string
        if (empty($baseEmail)) {
            return null;
        }
        
        $counter = 1;
        $email = $baseEmail . '@imported.local';
        
        while (Customer::where('email', $email)->exists()) {
            $email = $baseEmail . $counter . '@imported.local';
            $counter++;
        }
        
        return $email;
    }

    /**
     * Generate a placeholder phone number
     */
    private function generatePhone(): string
    {
        return 'N/A';
    }
}
