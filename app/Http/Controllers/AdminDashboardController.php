<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\JobOrder;
use App\Models\Technician;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $stats = [
            'total_customers' => Customer::count(),
            'total_job_orders' => JobOrder::count(),
            'pending_job_orders' => JobOrder::where('status', 'pending_dispatch')->count(),
            'in_progress_job_orders' => JobOrder::where('status', 'in_progress')->count(),
            'completed_job_orders' => JobOrder::where('status', 'completed')->count(),
            'total_technicians' => Technician::count(),
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
