<?php

namespace App\Http\Controllers;

use App\Models\JobOrder;
use App\Models\JobOrderMessage;
use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobOrderMessageController extends Controller
{
    /**
     * Get messages for a specific job order
     */
    public function index(JobOrder $jobOrder)
    {
        // Check if user has access to this job order
        if (!$this->userCanAccessJobOrder($jobOrder)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = $jobOrder->messages()
            ->with('user:id,name,role')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'user_name' => $message->user->name,
                    'user_role' => $message->user->role,
                    'is_read' => $message->is_read,
                    'created_at' => $message->created_at->format('M j, Y g:i A'),
                    'is_own_message' => $message->user_id === Auth::id(),
                ];
            });

        return response()->json($messages);
    }

    /**
     * Store a new message for a job order
     */
    public function store(Request $request, JobOrder $jobOrder)
    {
        // Check if user has access to this job order
        if (!$this->userCanAccessJobOrder($jobOrder)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = JobOrderMessage::create([
            'job_order_id' => $jobOrder->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_read' => false,
        ]);

        $message->load('user:id,name,role');

        return response()->json([
            'id' => $message->id,
            'message' => $message->message,
            'user_name' => $message->user->name,
            'user_role' => $message->user->role,
            'is_read' => $message->is_read,
            'created_at' => $message->created_at->format('M j, Y g:i A'),
            'is_own_message' => true,
        ]);
    }

    /**
     * Mark messages as read for a specific job order
     */
    public function markAsRead(JobOrder $jobOrder)
    {
        // Check if user has access to this job order
        if (!$this->userCanAccessJobOrder($jobOrder)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Mark all messages as read for this job order (except the user's own messages)
        $jobOrder->messages()
            ->where('user_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Get unread message count for a specific job order
     */
    public function getUnreadCount(JobOrder $jobOrder)
    {
        // Check if user has access to this job order
        if (!$this->userCanAccessJobOrder($jobOrder)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $unreadCount = $jobOrder->messages()
            ->where('user_id', '!=', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json(['unread_count' => $unreadCount]);
    }

    /**
     * Check if the current user can access the job order
     */
    private function userCanAccessJobOrder(JobOrder $jobOrder)
    {
        $user = Auth::user();
        
        // Admin can access all job orders
        if ($user->role === 'admin') {
            return true;
        }
        
        // Technician can only access job orders assigned to them
        if ($user->role === 'technician') {
            // Check if this user has a technician record and if that technician is assigned to this job
            $technician = Technician::where('user_id', $user->id)->first();
            if ($technician) {
                return $jobOrder->technician_id === $technician->id;
            }
        }
        
        return false;
    }
}
