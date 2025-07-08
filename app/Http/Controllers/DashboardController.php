<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\JobOrder;
use App\Models\Technician;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isTechnician()) {
            return $this->technicianDashboard();
        }
        
        // Default dashboard for other roles
        return view('dashboard', [
            'user' => $user,
            'isAdmin' => false,
            'isTechnician' => false,
        ]);
    }

    /**
     * Admin dashboard with system overview.
     */
    private function adminDashboard()
    {
        $stats = [
            'total_customers' => Customer::count(),
            'total_job_orders' => JobOrder::count(),
            'pending_job_orders' => JobOrder::where('status', 'pending_dispatch')->count(),
            'in_progress_job_orders' => JobOrder::where('status', 'in_progress')->count(),
            'completed_job_orders' => JobOrder::where('status', 'completed')->count(),
            'cancelled_job_orders' => JobOrder::where('status', 'cancelled')->count(),
            'total_technicians' => Technician::count(),
        ];

        // Only show job orders from today
        $recent_job_orders = JobOrder::with(['customer', 'technician.user'])
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recent_customers = Customer::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $urgent_job_orders = JobOrder::with(['customer', 'technician.user'])
            ->where('priority', 'urgent')
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', [
            'user' => Auth::user(),
            'isAdmin' => true,
            'isTechnician' => false,
            'stats' => $stats,
            'recent_job_orders' => $recent_job_orders,
            'recent_customers' => $recent_customers,
            'urgent_job_orders' => $urgent_job_orders,
        ]);
    }

    /**
     * Technician dashboard with assigned jobs.
     */
    private function technicianDashboard()
    {
        $user = Auth::user();
        $technician = $user->technician;
        
        if (!$technician) {
            // If technician profile doesn't exist, create it
            $technician = Technician::create([
                'user_id' => $user->id,
                'phone_number' => '', // Can be updated later
            ]);
        }

        // Only show job orders from today
        $my_job_orders = JobOrder::with(['customer'])
            ->where('technician_id', $technician->id)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->whereDate('created_at', today())
            ->orderBy('scheduled_at')
            ->get();

        $completed_jobs_today = JobOrder::where('technician_id', $technician->id)
            ->where('status', 'completed')
            ->whereDate('completed_at', today())
            ->count();

        $stats = [
            'pending_jobs' => $my_job_orders->where('status', 'pending_dispatch')->count(),
            'scheduled_jobs' => $my_job_orders->where('status', 'scheduled')->count(),
            'in_progress_jobs' => $my_job_orders->where('status', 'in_progress')->count(),
            'completed_today' => $completed_jobs_today,
        ];

        return view('dashboard', [
            'user' => $user,
            'isAdmin' => false,
            'isTechnician' => true,
            'technician' => $technician,
            'my_job_orders' => $my_job_orders,
            'stats' => $stats,
        ]);
    }
}
