<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Plan;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class CustomerController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of customers.
     */
    public function index(Request $request)
    {
        $query = Customer::with(['jobOrders', 'plan']);
        
        // Handle search
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('first_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('last_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('phone_number', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('service_address', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Filter by plan
        if ($request->has('plan_filter') && !empty($request->plan_filter)) {
            if ($request->plan_filter === 'no_plan') {
                $query->whereNull('plan_id');
            } else {
                $query->where('plan_id', $request->plan_filter);
            }
        }

        // Filter by plan status
        if ($request->has('status_filter') && !empty($request->status_filter)) {
            $query->where('plan_status', $request->status_filter);
        }

        // Filter by plan type
        if ($request->has('plan_type_filter') && !empty($request->plan_type_filter)) {
            $query->whereHas('plan', function ($q) use ($request) {
                $q->where('type', $request->plan_type_filter);
            });
        }

        // Filter by installation date range
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->where('plan_installed_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->where('plan_installed_at', '<=', $request->date_to);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');
        
        $allowedSortFields = ['first_name', 'last_name', 'email', 'created_at', 'plan_installed_at', 'name'];
        if (in_array($sortBy, $allowedSortFields)) {
            if ($sortBy === 'name') {
                // Sort by full name (first_name + last_name)
                $query->orderByRaw("CONCAT(first_name, ' ', last_name) {$sortDirection}");
            } else {
                $query->orderBy($sortBy, $sortDirection);
            }
        } else {
            // Default sorting by name
            $query->orderByRaw("CONCAT(first_name, ' ', last_name) ASC");
        }
        
        $customers = $query->paginate(15)->withQueryString();
        
        // Get data for filter dropdowns
        $plans = Plan::active()->orderBy('name')->get();
        $planTypes = Plan::distinct()->pluck('type')->sort();
        $planStatuses = Customer::PLAN_STATUSES;
        
        return view('admin.customers.index', compact('customers', 'plans', 'planTypes', 'planStatuses'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        $plans = Plan::active()->orderBy('name')->get();
        return view('admin.customers.create', compact('plans'));
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email',
            'phone_number' => 'nullable|string|max:20',
            'service_address' => 'required|string|max:1000',
            'plan_id' => 'nullable|exists:plans,id',
            'plan_installed_at' => 'nullable|date',
            'plan_status' => 'required_with:plan_id|in:' . implode(',', array_keys(Customer::PLAN_STATUSES)),
        ]);

        // Set default plan status if plan is selected
        if ($validated['plan_id'] && !$validated['plan_status']) {
            $validated['plan_status'] = 'active';
        }

        $customer = Customer::create($validated);

        return redirect()->route('admin.customers.show', $customer)
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified customer.
     */
    public function show(Customer $customer)
    {
        $customer->load(['jobOrders.technician', 'plan']);
        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer)
    {
        $plans = Plan::active()->orderBy('name')->get();
        return view('admin.customers.edit', compact('customer', 'plans'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        // Add debugging
        \Log::info('CustomerController@update called', [
            'customer_id' => $customer->id,
            'request_method' => $request->method(),
            'form_data' => $request->all()
        ]);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email,' . $customer->id,
            'phone_number' => 'nullable|string|max:20',
            'service_address' => 'required|string|max:1000',
            'plan_id' => 'nullable|exists:plans,id',
            'plan_installed_at' => 'nullable|date',
            'plan_status' => 'required_with:plan_id|in:' . implode(',', array_keys(Customer::PLAN_STATUSES)),
        ]);

        // Set default plan status if plan is selected
        if ($validated['plan_id'] && !$validated['plan_status']) {
            $validated['plan_status'] = 'active';
        }

        $customer->update($validated);

        \Log::info('Customer updated successfully', ['customer_id' => $customer->id]);

        return redirect()->route('admin.customers.show', $customer)
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(Customer $customer)
    {
        // Add debugging
        \Log::info('CustomerController@destroy called', [
            'customer_id' => $customer->id,
            'customer_name' => $customer->full_name
        ]);

        // Check if customer has any job orders
        if ($customer->jobOrders()->count() > 0) {
            \Log::warning('Cannot delete customer with job orders', ['customer_id' => $customer->id]);
            return redirect()->route('admin.customers.index')
                ->with('error', 'Cannot delete customer with existing job orders.');
        }

        $customer->delete();

        \Log::info('Customer deleted successfully', ['customer_id' => $customer->id]);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    /**
     * Search customers by name or email.
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $customers = Customer::where('first_name', 'LIKE', "%{$query}%")
            ->orWhere('last_name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get();

        return response()->json($customers);
    }

    /**
     * Export customers to CSV
     */
    public function export()
    {
        $customers = Customer::with(['plan'])
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'customers_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($customers) {
            $file = fopen('php://output', 'w');
            
            // Write CSV header matching import.customer.csv format
            fputcsv($file, ['Name', 'Address', 'Plan', 'Date Installed']);
            
            foreach ($customers as $customer) {
                $name = trim($customer->first_name . ' ' . $customer->last_name);
                $address = $customer->service_address ?: '';
                $plan = $customer->plan ? $customer->plan->name : '';
                $dateInstalled = $customer->plan_installed_at ? 
                    $customer->plan_installed_at->format('n/j/Y') : '';
                
                fputcsv($file, [$name, $address, $plan, $dateInstalled]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show the import form
     */
    public function importForm()
    {
        return view('admin.customers.import');
    }

    /**
     * Import customers from CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        try {
            $file = $request->file('file');
            $path = $file->getRealPath();
            $data = array_map('str_getcsv', file($path));
            
            // Remove header row
            $header = array_shift($data);
            
            $imported = 0;
            $skipped = 0;
            $errors = [];
            
            DB::beginTransaction();
            
            foreach ($data as $index => $row) {
                $rowNumber = $index + 2; // +2 because we removed header and arrays are 0-indexed
                
                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }
                
                // Ensure we have at least 4 columns
                while (count($row) < 4) {
                    $row[] = '';
                }
                
                [$name, $address, $planName, $dateInstalled] = $row;
                
                // Skip if no name
                if (empty(trim($name))) {
                    $skipped++;
                    continue;
                }
                
                // Parse name (assuming "First Last" format)
                $nameParts = explode(' ', trim($name), 2);
                $firstName = $nameParts[0];
                $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
                
                // Find or create plan
                $plan = null;
                if (!empty(trim($planName))) {
                    $plan = Plan::where('name', trim($planName))->first();
                    if (!$plan) {
                        // Create plan if it doesn't exist
                        $plan = Plan::create([
                            'name' => trim($planName),
                            'type' => 'internet', // Default type
                            'description' => 'Imported plan: ' . trim($planName),
                            'monthly_rate' => 1299.00, // Default rate
                            'is_active' => true,
                        ]);
                    }
                }
                
                // Parse installation date
                $installationDate = null;
                if (!empty(trim($dateInstalled))) {
                    try {
                        $installationDate = Carbon::createFromFormat('n/j/Y', trim($dateInstalled));
                    } catch (Exception $e) {
                        try {
                            $installationDate = Carbon::createFromFormat('m/d/Y', trim($dateInstalled));
                        } catch (Exception $e2) {
                            try {
                                $installationDate = Carbon::parse(trim($dateInstalled));
                            } catch (Exception $e3) {
                                $errors[] = "Row {$rowNumber}: Invalid date format '{$dateInstalled}'";
                                continue;
                            }
                        }
                    }
                }
                
                // Generate unique email
                $baseEmail = strtolower(str_replace(' ', '.', $firstName . '.' . $lastName));
                $email = $baseEmail . '@customer.local';
                $counter = 1;
                while (Customer::where('email', $email)->exists()) {
                    $email = $baseEmail . $counter . '@customer.local';
                    $counter++;
                }
                
                try {
                    // Create customer
                    $customer = Customer::create([
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'email' => $email,
                        'phone_number' => '', // Not in CSV
                        'service_address' => trim($address),
                        'plan_id' => $plan ? $plan->id : null,
                        'plan_installed_at' => $installationDate,
                        'plan_status' => $plan ? 'active' : null,
                    ]);
                    
                    $imported++;
                } catch (Exception $e) {
                    $errors[] = "Row {$rowNumber}: Failed to create customer - " . $e->getMessage();
                }
            }
            
            DB::commit();
            
            // Generate payment notices for imported customers if they have plans
            if ($imported > 0) {
                $paymentService = new PaymentService();
                $noticesGenerated = $paymentService->recalculateFromInstallationDates();
            }
            
            $message = "Import completed! Imported: {$imported}, Skipped: {$skipped}";
            if (count($errors) > 0) {
                $message .= ". Errors: " . implode(', ', array_slice($errors, 0, 3));
                if (count($errors) > 3) {
                    $message .= " and " . (count($errors) - 3) . " more...";
                }
            }
            
            return redirect()->route('admin.customers.index')
                ->with('success', $message);
                
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Customer import failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
}
