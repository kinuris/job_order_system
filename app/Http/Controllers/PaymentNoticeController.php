<?php

namespace App\Http\Controllers;

use App\Models\PaymentNotice;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PaymentNoticeController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {}

    /**
     * Get payment notices with sorting and filtering options.
     */
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'sort_by' => 'sometimes|string|in:name,unpaid_months,due_date,amount',
            'sort_direction' => 'sometimes|string|in:asc,desc',
            'status' => 'sometimes|array',
            'status.*' => 'string|in:pending,overdue,paid,cancelled',
            'customer_id' => 'sometimes|integer|exists:customers,id',
            'per_page' => 'sometimes|integer|min:1|max:100',
        ]);

        $options = [
            'sort_by' => $validated['sort_by'] ?? 'name',
            'sort_direction' => $validated['sort_direction'] ?? 'asc',
            'status' => $validated['status'] ?? ['pending', 'overdue'],
            'customer_id' => $validated['customer_id'] ?? null,
        ];

        $notices = $this->paymentService->getPaymentNoticesWithSorting($options);

        // Paginate if requested
        $perPage = $validated['per_page'] ?? null;
        if ($perPage) {
            $currentPage = $request->get('page', 1);
            $items = $notices->slice(($currentPage - 1) * $perPage, $perPage)->values();
            $total = $notices->count();
            
            return response()->json([
                'data' => $items,
                'pagination' => [
                    'current_page' => $currentPage,
                    'per_page' => $perPage,
                    'total' => $total,
                    'last_page' => ceil($total / $perPage),
                ]
            ]);
        }

        return response()->json([
            'data' => $notices->values(),
            'total' => $notices->count()
        ]);
    }

    /**
     * Get customers with payment notices summary.
     */
    public function customersSummary(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'sort_by' => 'sometimes|string|in:name,unpaid_months',
            'sort_direction' => 'sometimes|string|in:asc,desc',
            'group_by' => 'sometimes|string|in:unpaid_months,overdue_range,amount_range',
            'status_filter' => 'sometimes|string|in:pending,overdue,paid,cancelled',
        ]);

        $options = [
            'sort_by' => $validated['sort_by'] ?? 'name',
            'sort_direction' => $validated['sort_direction'] ?? 'asc',
            'group_by' => $validated['group_by'] ?? null,
            'status_filter' => $validated['status_filter'] ?? null,
        ];

        $summary = $this->paymentService->getPaymentNoticesSummary($options);

        return response()->json($summary);
    }

    /**
     * Get notices for a specific customer with sorting.
     */
    public function customerNotices(Request $request, int $customerId): JsonResponse
    {
        $validated = $request->validate([
            'sort_by' => 'sometimes|string|in:due_date,amount,status',
            'sort_direction' => 'sometimes|string|in:asc,desc',
            'status' => 'sometimes|array',
            'status.*' => 'string|in:pending,overdue,paid,cancelled',
        ]);

        $options = [
            'sort_by' => 'name', // We'll sort by due_date for individual customer
            'sort_direction' => $validated['sort_direction'] ?? 'asc',
            'status' => $validated['status'] ?? null,
            'customer_id' => $customerId,
        ];

        $notices = $this->paymentService->getPaymentNoticesWithSorting($options);

        // For individual customer, sort by due_date instead of name
        $sortBy = $validated['sort_by'] ?? 'due_date';
        if ($sortBy === 'due_date') {
            $notices = $notices->sortBy('due_date', SORT_REGULAR, $validated['sort_direction'] === 'desc');
        } elseif ($sortBy === 'amount') {
            $notices = $notices->sortBy('amount_due', SORT_NUMERIC, $validated['sort_direction'] === 'desc');
        } elseif ($sortBy === 'status') {
            $notices = $notices->sortBy('status', SORT_REGULAR, $validated['sort_direction'] === 'desc');
        }

        return response()->json([
            'data' => $notices->values(),
            'total' => $notices->count()
        ]);
    }

    /**
     * Get payment notices statistics.
     */
    public function statistics(): JsonResponse
    {
        $stats = $this->paymentService->getDashboardStats();
        
        // Add sorting statistics
        $customersByUnpaidMonths = $this->paymentService->getCustomersWithNoticesSorted([
            'sort_by' => 'unpaid_months',
            'sort_direction' => 'desc',
            'has_unpaid_only' => true
        ]);

        $unpaidMonthsDistribution = [];
        foreach ($customersByUnpaidMonths as $customer) {
            $months = $customer->getUnpaidMonths();
            $range = $this->getUnpaidMonthsRange($months);
            $unpaidMonthsDistribution[$range] = ($unpaidMonthsDistribution[$range] ?? 0) + 1;
        }

        $stats['unpaid_months_distribution'] = $unpaidMonthsDistribution;
        $stats['top_overdue_customers'] = $customersByUnpaidMonths->take(10)->map(function ($customer) {
            return [
                'id' => $customer->id,
                'name' => $customer->full_name,
                'unpaid_months' => $customer->getUnpaidMonths(),
                'total_overdue' => $customer->paymentNotices->sum('amount_due')
            ];
        });

        return response()->json($stats);
    }

    /**
     * Get range label for unpaid months.
     */
    private function getUnpaidMonthsRange(int $months): string
    {
        if ($months === 0) return '0 months';
        if ($months === 1) return '1 month';
        if ($months <= 3) return '2-3 months';
        if ($months <= 6) return '4-6 months';
        return '6+ months';
    }
}
