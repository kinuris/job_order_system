<?php

namespace App\Http\Controllers;

use App\Models\JobOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TechnicianJobOrderController extends Controller
{
    /**
     * Update job order status by technician.
     */
    public function updateStatus(Request $request, JobOrder $jobOrder)
    {
        $user = Auth::user();
        $technician = $user->technician;
        
        // Ensure the job is assigned to this technician
        if ($jobOrder->technician_id !== $technician->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:en_route,in_progress,on_hold,completed',
            'resolution_notes' => 'nullable|string|max:1000',
        ]);

        // Update timestamps based on status
        $updates = ['status' => $validated['status']];
        
        if ($validated['status'] === 'in_progress' && !$jobOrder->started_at) {
            $updates['started_at'] = now();
        }
        
        if ($validated['status'] === 'completed') {
            $updates['completed_at'] = now();
            if ($request->filled('resolution_notes')) {
                $updates['resolution_notes'] = $validated['resolution_notes'];
            }
        }

        $jobOrder->update($updates);

        return response()->json([
            'success' => true,
            'message' => 'Job order status updated successfully',
            'job' => $jobOrder->fresh()
        ]);
    }

    /**
     * Update job order notes by technician.
     */
    public function updateNotes(Request $request, JobOrder $jobOrder)
    {
        $user = Auth::user();
        $technician = $user->technician;
        
        // Ensure the job is assigned to this technician
        if ($jobOrder->technician_id !== $technician->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'resolution_notes' => 'nullable|string|max:1000',
        ]);

        $jobOrder->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Notes updated successfully'
        ]);
    }

    /**
     * Reschedule a job order.
     */
    public function reschedule(Request $request, JobOrder $jobOrder)
    {
        $user = Auth::user();
        $technician = $user->technician;
        
        // Ensure the job is assigned to this technician
        if ($jobOrder->technician_id !== $technician->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Only allow rescheduling if not completed or cancelled
        if (in_array($jobOrder->status, ['completed', 'cancelled'])) {
            return response()->json(['error' => 'Cannot reschedule completed or cancelled jobs'], 400);
        }

        $validated = $request->validate([
            'scheduled_at' => 'required|date|after:now',
        ]);

        $jobOrder->update([
            'scheduled_at' => $validated['scheduled_at'],
            'status' => 'scheduled'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Job rescheduled successfully',
            'job' => $jobOrder->fresh()
        ]);
    }
}
