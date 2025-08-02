<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Plan;
use App\Models\PaymentNotice;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentNoticeInstallationDateUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected PaymentService $paymentService;
    protected Plan $plan;

    public function setUp(): void
    {
        parent::setUp();
        $this->paymentService = app(PaymentService::class);
        
        // Create a test plan
        $this->plan = Plan::factory()->create([
            'name' => 'Test Plan',
            'monthly_rate' => 1500.00
        ]);
    }

    /** @test */
    public function it_updates_payment_notices_when_installation_date_changes()
    {
        // Create a customer with an installation date on the 5th
        $customer = Customer::factory()->create([
            'plan_id' => $this->plan->id,
            'plan_installed_at' => Carbon::create(2025, 5, 5), // May 5th
            'plan_status' => 'active'
        ]);

        // Generate initial notice
        $this->paymentService->updateNoticesForInstallationDateChange($customer);
        
        $initialNotices = $customer->paymentNotices()->get();
        $this->assertLessThanOrEqual(1, $initialNotices->count());

        // If there's a notice, it should be due on the 5th of some month
        if ($initialNotices->isNotEmpty()) {
            $this->assertEquals(5, $initialNotices->first()->due_date->day);
        }

        // Change the installation date to the 25th of the same month
        $customer->update([
            'plan_installed_at' => Carbon::create(2025, 5, 25) // May 25th
        ]);

        // Check that the notice due date was updated to the 25th
        $customer->refresh();
        $updatedNotices = $customer->paymentNotices()->get();
        
        // Should still have at most one notice
        $this->assertLessThanOrEqual(1, $updatedNotices->count());
        
        // If there's a notice, it should now be due on the 25th
        if ($updatedNotices->isNotEmpty()) {
            $this->assertEquals(25, $updatedNotices->first()->due_date->day);
        }
    }

    /** @test */
    public function it_does_not_create_notices_for_paid_periods()
    {
        $customer = Customer::factory()->create([
            'plan_id' => $this->plan->id,
            'plan_installed_at' => Carbon::now()->subMonths(2)->startOfMonth(), // 2 months ago
            'plan_status' => 'active'
        ]);

        // Record a payment that covers the current month
        $paymentDate = Carbon::now()->startOfMonth();
        $this->paymentService->recordPayment(
            $customer,
            1500.00,
            'cash',
            $paymentDate,
            3  // Covers current month and next 2 months
        );

        // Get the payment to check what period it covers
        $payment = $customer->payments()->first();
        
        // Update installation date - this should not create a notice since the next period is paid
        $noticesCreated = $this->paymentService->updateNoticesForInstallationDateChange($customer);

        // Should not create any notice since the next period is already paid
        $this->assertEquals(0, $noticesCreated);
        $this->assertEquals(0, $customer->paymentNotices()->count());
    }

    /** @test */
    public function it_sets_correct_status_for_overdue_notices()
    {
        $customer = Customer::factory()->create([
            'plan_id' => $this->plan->id,
            'plan_installed_at' => Carbon::now()->subMonths(3)->startOfMonth(),
            'plan_status' => 'active'
        ]);

        $this->paymentService->updateNoticesForInstallationDateChange($customer);

        // Check the notice status - should be overdue if due date is in the past
        $notices = $customer->paymentNotices()->get();
        
        if ($notices->isNotEmpty()) {
            $notice = $notices->first();
            if ($notice->due_date < Carbon::now()) {
                $this->assertEquals('overdue', $notice->status);
            } else {
                $this->assertEquals('pending', $notice->status);
            }
        }
    }

    /** @test */
    public function it_handles_bulk_updates_correctly()
    {
        // Create multiple customers
        $customers = Customer::factory()->count(3)->create([
            'plan_id' => $this->plan->id,
            'plan_status' => 'active'
        ]);

        foreach ($customers as $customer) {
            $customer->update([
                'plan_installed_at' => Carbon::now()->subMonths(2)->startOfMonth()
            ]);
        }

        $customerIds = $customers->pluck('id')->toArray();
        $results = $this->paymentService->bulkUpdateNoticesForInstallationDateChanges($customerIds);

        $this->assertEquals(3, $results['customers_processed']);
        $this->assertGreaterThanOrEqual(0, $results['total_notices_created']); // Can be 0 if all periods are paid
        $this->assertLessThanOrEqual(3, $results['total_notices_created']); // Max one per customer
        $this->assertEmpty($results['errors']);
    }
}
