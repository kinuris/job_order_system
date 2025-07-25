<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Plan;
use Illuminate\Http\Request;

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
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        
        $allowedSortFields = ['first_name', 'last_name', 'email', 'created_at', 'plan_installed_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
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

        return redirect()->route('admin.customers.show', $customer)
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(Customer $customer)
    {
        // Check if customer has any job orders
        if ($customer->jobOrders()->count() > 0) {
            return redirect()->route('admin.customers.index')
                ->with('error', 'Cannot delete customer with existing job orders.');
        }

        $customer->delete();

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
}
