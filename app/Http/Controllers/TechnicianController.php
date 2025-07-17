<?php

namespace App\Http\Controllers;

use App\Models\Technician;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TechnicianController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of technicians.
     */
    public function index(Request $request)
    {
        $query = Technician::with(['user', 'jobOrders', 'activeJobOrders']);
        
        // Handle search
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->whereHas('user', function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('username', 'LIKE', "%{$searchTerm}%");
            });
        }
        
        $technicians = $query->paginate(15);
        
        return view('admin.technicians.index', compact('technicians'));
    }

    /**
     * Show the form for creating a new technician.
     */
    public function create()
    {
        return view('admin.technicians.create');
    }

    /**
     * Store a newly created technician in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'nullable|string|max:20',
        ]);

        // Create the user first
        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => 'technician',
        ]);

        // Create the technician record
        $technician = Technician::create([
            'user_id' => $user->id,
            'phone_number' => $validated['phone_number'],
        ]);

        return redirect()->route('admin.technicians.show', $technician)
            ->with('success', 'Technician created successfully.');
    }

    /**
     * Display the specified technician.
     */
    public function show(Technician $technician)
    {
        $technician->load(['user', 'jobOrders.customer', 'activeJobOrders']);
        
        return view('admin.technicians.show', compact('technician'));
    }

    /**
     * Show the form for editing the specified technician.
     */
    public function edit(Technician $technician)
    {
        $technician->load('user');
        
        return view('admin.technicians.edit', compact('technician'));
    }

    /**
     * Update the specified technician in storage.
     */
    public function update(Request $request, Technician $technician)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $technician->user_id,
            'phone_number' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update the user
        $userData = [
            'name' => $validated['name'],
            'username' => $validated['username'],
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $technician->user->update($userData);

        // Update the technician
        $technician->update([
            'phone_number' => $validated['phone_number'],
        ]);

        return redirect()->route('admin.technicians.show', $technician)
            ->with('success', 'Technician updated successfully.');
    }

    /**
     * Remove the specified technician from storage.
     */
    public function destroy(Technician $technician)
    {
        // Check if technician has any active job orders
        if ($technician->activeJobOrders()->count() > 0) {
            return redirect()->route('admin.technicians.index')
                ->with('error', 'Cannot delete technician with active job orders.');
        }

        // Delete the technician first, then the user
        $user = $technician->user;
        $technician->delete();
        $user->delete();

        return redirect()->route('admin.technicians.index')
            ->with('success', 'Technician deleted successfully.');
    }

    /**
     * Search technicians by name or username.
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $technicians = Technician::with('user')
            ->whereHas('user', function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('username', 'LIKE', "%{$query}%");
            })
            ->limit(10)
            ->get();

        return response()->json($technicians->map(function ($technician) {
            return [
                'id' => $technician->id,
                'name' => $technician->user->name,
                'username' => $technician->user->username,
                'phone_number' => $technician->phone_number,
            ];
        }));
    }
}
