<?php

namespace App\Http\Controllers;

use App\Models\Technician;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    /**
     * Export technicians to CSV.
     */
    public function export()
    {
        $technicians = Technician::with('user')->orderBy('created_at')->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="technicians_' . date('Y-m-d_H-i-s') . '.csv"',
        ];
        
        $callback = function() use ($technicians) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'Name',
                'Username', 
                'Phone Number',
                'Created Date',
                'Total Job Orders',
                'Active Job Orders'
            ]);
            
            foreach ($technicians as $technician) {
                fputcsv($file, [
                    $technician->user->name,
                    $technician->user->username,
                    $technician->phone_number ?: '',
                    $technician->created_at->format('Y-m-d'),
                    $technician->jobOrders()->count(),
                    $technician->activeJobOrders()->count()
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
    
    /**
     * Show the form for importing technicians.
     */
    public function importForm()
    {
        return view('admin.technicians.import');
    }
    
    /**
     * Import technicians from CSV file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);
        
        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        
        try {
            DB::beginTransaction();
            
            $imported = 0;
            $skipped = 0;
            $errors = 0;
            $errorMessages = [];
            
            if (($handle = fopen($path, 'r')) !== false) {
                // Skip header row
                $header = fgetcsv($handle);
                
                while (($row = fgetcsv($handle)) !== false) {
                    try {
                        // Skip empty rows
                        if (empty(array_filter($row))) {
                            continue;
                        }
                        
                        $name = trim($row[0] ?? '');
                        $username = trim($row[1] ?? '');
                        $phoneNumber = trim($row[2] ?? '');
                        $password = trim($row[3] ?? '');
                        
                        // Validate required fields
                        if (empty($name) || empty($username)) {
                            $skipped++;
                            $errorMessages[] = "Skipped row: Name and username are required";
                            continue;
                        }
                        
                        // Generate password if not provided
                        if (empty($password)) {
                            $password = 'password123'; // Default password
                        }
                        
                        // Check if username already exists
                        if (User::where('username', $username)->exists()) {
                            $skipped++;
                            $errorMessages[] = "Skipped: Username '{$username}' already exists";
                            continue;
                        }
                        
                        // Create user
                        $user = User::create([
                            'name' => $name,
                            'username' => $username,
                            'password' => Hash::make($password),
                            'role' => 'technician',
                        ]);
                        
                        // Create technician
                        Technician::create([
                            'user_id' => $user->id,
                            'phone_number' => $phoneNumber ?: null,
                        ]);
                        
                        $imported++;
                        
                    } catch (\Exception $e) {
                        $errors++;
                        $errorMessages[] = "Error importing row: " . $e->getMessage();
                        Log::error("Technician import error", [
                            'row' => $row,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
                
                fclose($handle);
            }
            
            DB::commit();
            
            $message = "Import completed. Imported: {$imported}, Skipped: {$skipped}, Errors: {$errors}";
            
            if (!empty($errorMessages)) {
                $message .= " Issues: " . implode('; ', array_slice($errorMessages, 0, 5));
                if (count($errorMessages) > 5) {
                    $message .= " and " . (count($errorMessages) - 5) . " more...";
                }
            }
            
            return redirect()->route('admin.technicians.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Technician import failed", ['error' => $e->getMessage()]);
            
            return redirect()->route('admin.technicians.index')
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
}
