<?php

namespace App\Traits;

use App\Models\Customer;
use App\Services\PaymentService;
use Carbon\Carbon;

trait UpdatesPaymentNotices
{
    /**
     * Update payment notices for a customer after installation date change.
     * This can be called from controllers after updating customer data.
     */
    protected function updateCustomerPaymentNotices(Customer $customer): array
    {
        $paymentService = app(PaymentService::class);
        $noticesCreated = $paymentService->updateNoticesForInstallationDateChange($customer);
        
        return [
            'notices_created' => $noticesCreated,
            'message' => $noticesCreated > 0 
                ? "Payment notices updated successfully. Created {$noticesCreated} new notices."
                : "No new payment notices were needed."
        ];
    }

    /**
     * Update payment notices for multiple customers.
     */
    protected function updateMultipleCustomerPaymentNotices(array $customerIds = null): array
    {
        $paymentService = app(PaymentService::class);
        return $paymentService->bulkUpdateNoticesForInstallationDateChanges($customerIds);
    }

    /**
     * Clear payment notices for a customer.
     */
    protected function clearCustomerPaymentNotices(Customer $customer, array $statuses = ['pending', 'overdue']): array
    {
        $paymentService = app(PaymentService::class);
        
        $deletedCount = $paymentService->clearPaymentNotices([
            'customer_id' => $customer->id,
            'status' => $statuses
        ])['deleted_count'];
        
        return [
            'deleted_count' => $deletedCount,
            'message' => $deletedCount > 0 
                ? "Successfully cleared {$deletedCount} payment notices."
                : "No payment notices found to clear."
        ];
    }

    /**
     * Clear old payment notices.
     */
    protected function clearOldPaymentNotices(Carbon $beforeDate, array $statuses = ['pending', 'overdue']): array
    {
        $paymentService = app(PaymentService::class);
        $deletedCount = $paymentService->clearNoticesOlderThan($beforeDate, $statuses);
        
        return [
            'deleted_count' => $deletedCount,
            'message' => $deletedCount > 0 
                ? "Successfully cleared {$deletedCount} old payment notices."
                : "No old payment notices found to clear."
        ];
    }
}
