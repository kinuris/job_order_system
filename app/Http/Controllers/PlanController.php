<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of plans.
     */
    public function index()
    {
        $plans = Plan::withCount('customers')
            ->orderBy('is_active', 'desc')
            ->orderBy('name')
            ->paginate(15);
        
        return view('admin.plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new plan.
     */
    public function create()
    {
        return view('admin.plans.create');
    }

    /**
     * Store a newly created plan in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:' . implode(',', array_keys(Plan::TYPES)),
            'description' => 'nullable|string|max:1000',
            'monthly_rate' => 'required|numeric|min:0|max:9999.99',
            'speed_mbps' => 'nullable|integer|min:1|max:10000',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $plan = Plan::create($validated);

        return redirect()->route('admin.plans.show', $plan)
            ->with('success', 'Plan created successfully.');
    }

    /**
     * Display the specified plan.
     */
    public function show(Plan $plan)
    {
        $plan->load('customers');
        return view('admin.plans.show', compact('plan'));
    }

    /**
     * Show the form for editing the specified plan.
     */
    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    /**
     * Update the specified plan in storage.
     */
    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:' . implode(',', array_keys(Plan::TYPES)),
            'description' => 'nullable|string|max:1000',
            'monthly_rate' => 'required|numeric|min:0|max:9999.99',
            'speed_mbps' => 'nullable|integer|min:1|max:10000',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $plan->update($validated);

        return redirect()->route('admin.plans.show', $plan)
            ->with('success', 'Plan updated successfully.');
    }

    /**
     * Remove the specified plan from storage.
     */
    public function destroy(Plan $plan)
    {
        // Check if plan has customers
        if ($plan->customers()->count() > 0) {
            return redirect()->route('admin.plans.index')
                ->with('error', 'Cannot delete plan that has customers assigned to it.');
        }

        $plan->delete();

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan deleted successfully.');
    }
}
