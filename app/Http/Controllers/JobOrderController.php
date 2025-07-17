<?php

namespace App\Http\Controllers;

use App\Models\JobOrder;
use App\Models\Customer;
use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobOrderController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of job orders.
     */
    public function index()
    {
        $currentUserId = Auth::id();
        
        $jobOrders = JobOrder::with(['customer', 'technician'])
            ->withCount(['unreadMessages as unread_messages_count' => function ($query) use ($currentUserId) {
                $query->where('user_id', '!=', $currentUserId);
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.job-orders.index', compact('jobOrders'));
    }

    /**
     * Show the form for creating a new job order.
     */
    public function create()
    {
        $customers = Customer::orderBy('first_name')->get();
        $technicians = Technician::with('user')->orderBy('created_at')->get();
        
        return view('admin.job-orders.create', compact('customers', 'technicians'));
    }

    /**
     * Store a newly created job order in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'technician_id' => 'nullable|exists:technicians,id',
            'type' => 'required|in:' . implode(',', array_keys(JobOrder::TYPES)),
            'priority' => 'required|in:' . implode(',', array_keys(JobOrder::PRIORITIES)),
            'description' => 'required|string|max:2000',
        ]);

        // Set default status and automatically schedule for today
        $validated['status'] = 'pending_dispatch';
        $validated['scheduled_at'] = now()->startOfDay()->addHours(8); // Schedule for 8 AM today

        $jobOrder = JobOrder::create($validated);

        return redirect()->route('admin.job-orders.show', $jobOrder)
            ->with('success', 'Job order created successfully and scheduled for today.');
    }

    /**
     * Display the specified job order.
     */
    public function show(JobOrder $jobOrder)
    {
        $jobOrder->load(['customer', 'technician.user']);
        return view('admin.job-orders.show', compact('jobOrder'));
    }

    /**
     * Show the form for editing the specified job order.
     */
    public function edit(JobOrder $jobOrder)
    {
        $customers = Customer::orderBy('first_name')->get();
        $technicians = Technician::with('user')->orderBy('created_at')->get();
        
        return view('admin.job-orders.edit', compact('jobOrder', 'customers', 'technicians'));
    }

    /**
     * Update the specified job order in storage.
     */
    public function update(Request $request, JobOrder $jobOrder)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'technician_id' => 'nullable|exists:technicians,id',
            'type' => 'required|in:' . implode(',', array_keys(JobOrder::TYPES)),
            'status' => 'required|in:' . implode(',', array_keys(JobOrder::STATUSES)),
            'priority' => 'required|in:' . implode(',', array_keys(JobOrder::PRIORITIES)),
            'description' => 'required|string|max:2000',
            'resolution_notes' => 'nullable|string|max:2000',
            'scheduled_at' => 'nullable|date',
            'started_at' => 'nullable|date',
            'completed_at' => 'nullable|date',
        ]);

        $jobOrder->update($validated);

        return redirect()->route('admin.job-orders.show', $jobOrder)
            ->with('success', 'Job order updated successfully.');
    }

    /**
     * Remove the specified job order from storage.
     */
    public function destroy(JobOrder $jobOrder)
    {
        // Only allow deletion of pending or cancelled job orders
        if (!in_array($jobOrder->status, ['pending_dispatch', 'cancelled'])) {
            return redirect()->route('admin.job-orders.index')
                ->with('error', 'Only pending or cancelled job orders can be deleted.');
        }

        $jobOrder->delete();

        return redirect()->route('admin.job-orders.index')
            ->with('success', 'Job order deleted successfully.');
    }

    /**
     * Assign a technician to a job order.
     */
    public function assignTechnician(Request $request, JobOrder $jobOrder)
    {
        $validated = $request->validate([
            'technician_id' => 'required|exists:technicians,id',
        ]);

        $jobOrder->update([
            'technician_id' => $validated['technician_id'],
            'status' => 'scheduled',
        ]);

        return redirect()->route('admin.job-orders.show', $jobOrder)
            ->with('success', 'Technician assigned successfully.');
    }

    /**
     * Update job order status.
     */
    public function updateStatus(Request $request, JobOrder $jobOrder)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(JobOrder::STATUSES)),
        ]);

        $updateData = ['status' => $validated['status']];

        // Auto-set timestamps based on status
        switch ($validated['status']) {
            case 'in_progress':
                if (!$jobOrder->started_at) {
                    $updateData['started_at'] = now();
                }
                break;
            case 'completed':
                if (!$jobOrder->completed_at) {
                    $updateData['completed_at'] = now();
                }
                break;
        }

        $jobOrder->update($updateData);

        return redirect()->route('admin.job-orders.show', $jobOrder)
            ->with('success', 'Job order status updated successfully.');
    }

    /**
     * Get job orders by status for dashboard.
     */
    public function getByStatus(Request $request)
    {
        $status = $request->get('status');
        
        $query = JobOrder::with(['customer', 'technician.user']);
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $jobOrders = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return response()->json($jobOrders);
    }
}
