<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\JobOrder;
use App\Models\Technician;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    protected $paymentService;

    /**
     * Create a new controller instance.
     */
    public function __construct(PaymentService $paymentService)
    {
        $this->middleware('admin');
        $this->paymentService = $paymentService;
    }

    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Get payment statistics
        $paymentStats = $this->paymentService->getDashboardStats();
        
        $stats = [
            'total_customers' => Customer::count(),
            'total_job_orders' => JobOrder::count(),
            'pending_job_orders' => JobOrder::where('status', 'pending_dispatch')->count(),
            'in_progress_job_orders' => JobOrder::where('status', 'in_progress')->count(),
            'completed_job_orders' => JobOrder::where('status', 'completed')->count(),
            'total_technicians' => Technician::count(),
            // Add payment statistics
            'overdue_notices' => $paymentStats['overdue_notices'],
            'due_this_week' => $paymentStats['due_this_week'],
            'total_unpaid' => $paymentStats['total_unpaid'],
            'monthly_collected' => $paymentStats['monthly_collected'],
            'customers_with_unpaid' => $paymentStats['customers_with_unpaid'] ?? 0,
            'pending_notices' => $paymentStats['pending_notices'] ?? 0,
            'avg_monthly_collection' => $paymentStats['avg_monthly_collection'] ?? 0,
        ];

        $recent_job_orders = JobOrder::with(['customer', 'technician.user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recent_customers = Customer::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_job_orders', 'recent_customers'));
    }
}
