<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Volt;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\JobOrderController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TechnicianJobOrderController;
use App\Http\Controllers\JobOrderMessageController;
use App\Http\Controllers\Admin\PaymentController;
use App\Models\JobOrder;

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
    
    // Customer import/export routes
    Route::get('customers-export', [CustomerController::class, 'export'])->name('customers.export');
    Route::get('customers-import', [CustomerController::class, 'importForm'])->name('customers.import.form');
    Route::post('customers-import', [CustomerController::class, 'import'])->name('customers.import');
    
    // Plan management routes
    Route::resource('plans', PlanController::class);
    
    // Technician management routes
    Route::resource('technicians', TechnicianController::class);
    Route::get('technicians/search', [TechnicianController::class, 'search'])->name('technicians.search');
    Route::get('technicians-export', [TechnicianController::class, 'export'])->name('technicians.export');
    Route::get('technicians-import', [TechnicianController::class, 'importForm'])->name('technicians.import.form');
    Route::post('technicians-import', [TechnicianController::class, 'import'])->name('technicians.import');
    
    // Job order management routes
    Route::resource('job-orders', JobOrderController::class);
    Route::patch('job-orders/{jobOrder}/assign-technician', [JobOrderController::class, 'assignTechnician'])
        ->name('job-orders.assign-technician');
    Route::patch('job-orders/{jobOrder}/update-status', [JobOrderController::class, 'updateStatus'])
        ->name('job-orders.update-status');
    Route::get('job-orders/status/{status?}', [JobOrderController::class, 'getByStatus'])
        ->name('job-orders.by-status');
    
    // Payment management routes
    Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('payments/customer/{customer}', [PaymentController::class, 'customer'])->name('payments.customer');
    Route::post('payments/generate-notices', [PaymentController::class, 'generateNotices'])->name('payments.generate-notices');
    Route::post('payments/update-overdue', [PaymentController::class, 'updateOverdue'])->name('payments.update-overdue');
    Route::patch('payment-notices/{notice}/mark-paid', [PaymentController::class, 'markNoticePaid'])->name('payment-notices.mark-paid');
    Route::patch('payment-notices/{notice}/cancel', [PaymentController::class, 'cancelNotice'])->name('payment-notices.cancel');
    
    // Payment notices API routes for sorting and filtering
    Route::get('api/payment-notices', [\App\Http\Controllers\PaymentNoticeController::class, 'index'])->name('api.payment-notices.index');
    Route::get('api/payment-notices/customers-summary', [\App\Http\Controllers\PaymentNoticeController::class, 'customersSummary'])->name('api.payment-notices.customers-summary');
    Route::get('api/payment-notices/statistics', [\App\Http\Controllers\PaymentNoticeController::class, 'statistics'])->name('api.payment-notices.statistics');
    Route::get('api/payment-notices/customer/{customer}', [\App\Http\Controllers\PaymentNoticeController::class, 'customerNotices'])->name('api.payment-notices.customer');
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

// Job order messages/chat routes (accessible by admin and assigned technician)
Route::middleware(['auth'])->prefix('job-orders/{jobOrder}/messages')->name('job-orders.messages.')->group(function () {
    Route::get('/', [JobOrderMessageController::class, 'index'])->name('index');
    Route::post('/', [JobOrderMessageController::class, 'store'])->name('store');
    Route::patch('/mark-read', [JobOrderMessageController::class, 'markAsRead'])->name('mark-read');
    Route::get('/unread-count', [JobOrderMessageController::class, 'getUnreadCount'])->name('unread-count');
});

require __DIR__.'/auth.php';
