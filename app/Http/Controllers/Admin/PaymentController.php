<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerPayment;
use App\Models\PaymentNotice;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Display payment dashboard.
     */
    public function index(Request $request)
    {
        $stats = $this->paymentService->getDashboardStats();
        
        // Get payment notices with filters
        $query = PaymentNotice::with(['customer', 'plan']);
        
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('overdue_only') && $request->overdue_only) {
            $query->overdue();
        }
        
        if ($request->has('customer_search') && $request->customer_search !== '') {
            $searchTerm = $request->customer_search;
            $query->whereHas('customer', function ($q) use ($searchTerm) {
                $q->where('first_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('last_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('email', 'LIKE', "%{$searchTerm}%");
            });
        }
        
        $notices = $query->orderBy('due_date', 'asc')->paginate(15);
        
        // Calculate unpaid months for each customer using the model method
        $customerUnpaidCounts = [];
        foreach ($notices as $notice) {
            $customerId = $notice->customer_id;
            if (!isset($customerUnpaidCounts[$customerId])) {
                $customerUnpaidCounts[$customerId] = $notice->customer->getUnpaidMonths();
            }
        }
        
        return view('admin.payments.index', compact('stats', 'notices', 'customerUnpaidCounts'));
    }

    /**
     * Show payment form for a customer.
     */
    public function create(Request $request)
    {
        $customer = null;
        
        if ($request->has('customer_id')) {
            $customer = Customer::with('plan')->findOrFail($request->customer_id);
        }
        
        $customers = Customer::with('plan')
            ->whereNotNull('plan_id')
            ->where('plan_status', 'active')
            ->orderBy('first_name')
            ->get();

        // Get paid months information for each customer
        $customersPaidMonths = [];
        foreach ($customers as $cust) {
            if ($cust->plan_installed_at) {
                try {
                    $paidMonths = $this->getPaidMonthsForCustomer($cust);
                    $customersPaidMonths[$cust->id] = $paidMonths;
                } catch (\Exception $e) {
                    // If there's an error getting paid months, default to empty array
                    $customersPaidMonths[$cust->id] = [];
                }
            } else {
                $customersPaidMonths[$cust->id] = [];
            }
        }
            
        return view('admin.payments.create', compact('customer', 'customers', 'customersPaidMonths'));
    }

    /**
     * Get paid months for a customer
     */
    private function getPaidMonthsForCustomer(Customer $customer): array
    {
        if (!$customer->plan_installed_at) {
            return [];
        }

        $payments = CustomerPayment::where('customer_id', $customer->id)
            ->where('status', 'confirmed')
            ->get();

        $paidMonths = [];
        
        foreach ($payments as $payment) {
            $currentMonth = $payment->period_from->copy()->startOfMonth();
            $endMonth = $payment->period_to->copy()->startOfMonth();
            
            while ($currentMonth <= $endMonth) {
                $monthKey = $currentMonth->format('Y-m');
                $paidMonths[] = $monthKey;
                $currentMonth->addMonth();
            }
        }

        return array_unique($paidMonths);
    }

    /**
     * Store a new payment.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:' . implode(',', array_keys(CustomerPayment::PAYMENT_METHODS)),
            'payment_date' => 'required|date|before_or_equal:today',
            'months_covered' => 'required|integer|min:1',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $customer = Customer::with('plan')->findOrFail($request->customer_id);
        
        if (!$customer->plan) {
            return back()->withErrors(['customer_id' => 'Customer must have an active plan.']);
        }

        $payment = $this->paymentService->recordPayment(
            $customer,
            $request->amount,
            $request->payment_method,
            Carbon::parse($request->payment_date),
            $request->months_covered,
            $request->reference_number,
            $request->notes
        );

        return redirect()->route('admin.payments.show', $payment)
            ->with('success', 'Payment recorded successfully!');
    }

    /**
     * Display payment details.
     */
    public function show(CustomerPayment $payment)
    {
        $payment->load(['customer', 'plan']);
        $summary = $this->paymentService->getCustomerPaymentSummary($payment->customer);
        
        return view('admin.payments.show', compact('payment', 'summary'));
    }

    /**
     * Show customer payment history.
     */
    public function customer(Customer $customer)
    {
        $customer->load(['plan', 'payments.plan', 'paymentNotices']);
        
        $payments = $customer->payments()
            ->orderBy('payment_date', 'desc')
            ->paginate(10);
            
        $notices = $customer->paymentNotices()
            ->orderBy('due_date', 'desc')
            ->paginate(10);
            
        $summary = $this->paymentService->getCustomerPaymentSummary($customer);
        
        return view('admin.payments.customer', compact('customer', 'payments', 'notices', 'summary'));
    }

    /**
     * Generate monthly notices.
     */
    public function generateNotices()
    {
        $count = $this->paymentService->generateMonthlyNotices();
        
        return back()->with('success', "Generated {$count} payment notices.");
    }

    /**
     * Update overdue statuses.
     */
    public function updateOverdue()
    {
        $count = $this->paymentService->updateOverdueStatuses();
        
        return back()->with('success', "Updated {$count} overdue notices.");
    }

    /**
     * Mark a notice as paid.
     */
    public function markNoticePaid(PaymentNotice $notice)
    {
        $notice->markAsPaid();
        
        return back()->with('success', 'Payment notice marked as paid.');
    }

    /**
     * Cancel a payment notice.
     */
    public function cancelNotice(PaymentNotice $notice)
    {
        $notice->update(['status' => 'cancelled']);
        
        return back()->with('success', 'Payment notice cancelled.');
    }
}
