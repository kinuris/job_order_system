<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\JobOrderController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TechnicianJobOrderController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// Admin routes - only accessible by users with admin role
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Customer management routes
    Route::resource('customers', CustomerController::class);
    Route::get('customers/search', [CustomerController::class, 'search'])->name('customers.search');
    
    // Job order management routes
    Route::resource('job-orders', JobOrderController::class);
    Route::patch('job-orders/{jobOrder}/assign-technician', [JobOrderController::class, 'assignTechnician'])
        ->name('job-orders.assign-technician');
    Route::patch('job-orders/{jobOrder}/update-status', [JobOrderController::class, 'updateStatus'])
        ->name('job-orders.update-status');
    Route::get('job-orders/status/{status?}', [JobOrderController::class, 'getByStatus'])
        ->name('job-orders.by-status');
});

// Technician routes (for managing their assigned job orders)
Route::middleware(['auth'])->prefix('technician')->name('technician.')->group(function () {
    Route::patch('job-orders/{jobOrder}/status', [TechnicianJobOrderController::class, 'updateStatus'])
        ->name('job-orders.update-status');
    Route::patch('job-orders/{jobOrder}/notes', [TechnicianJobOrderController::class, 'updateNotes'])
        ->name('job-orders.update-notes');
    Route::patch('job-orders/{jobOrder}/reschedule', [TechnicianJobOrderController::class, 'reschedule'])
        ->name('job-orders.reschedule');
});

require __DIR__.'/auth.php';
