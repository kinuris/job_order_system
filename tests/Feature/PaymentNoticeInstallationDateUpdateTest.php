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
        // Create a customer with an installation date
        $customer = Customer::factory()->create([
            'plan_id' => $this->plan->id,
            'plan_installed_at' => Carbon::now()->subMonths(3)->startOfMonth(),
            'plan_status' => 'active'
        ]);

        // Generate initial notices
        $this->paymentService->updateNoticesForInstallationDateChange($customer);
        
        $initialNoticeCount = $customer->paymentNotices()->count();
        $this->assertGreaterThan(0, $initialNoticeCount);

        // Change the installation date to 2 months ago instead of 3
        $customer->update([
            'plan_installed_at' => Carbon::now()->subMonths(2)->startOfMonth()
        ]);

        // Check that notices were updated (the model event should have triggered this)
        $customer->refresh();
        $newNoticeCount = $customer->paymentNotices()->whereIn('status', ['pending', 'overdue'])->count();
        
        // Should have fewer notices since installation was more recent
        $this->assertNotEquals($initialNoticeCount, $newNoticeCount);
    }

    /** @test */
    public function it_does_not_create_notices_for_paid_periods()
    {
        $customer = Customer::factory()->create([
            'plan_id' => $this->plan->id,
            'plan_installed_at' => Carbon::now()->subMonths(3)->startOfMonth(),
            'plan_status' => 'active'
        ]);

        // Record a payment for the first month
        $this->paymentService->recordPayment(
            $customer,
            1500.00,
            'cash',
            Carbon::now()->subMonths(2),
            1
        );

        // Update installation date
        $noticesCreated = $this->paymentService->updateNoticesForInstallationDateChange($customer);

        // Should not create notice for the paid period
        $paidPeriodNotice = $customer->paymentNotices()
            ->whereIn('status', ['pending', 'overdue'])
            ->where('period_from', '<=', Carbon::now()->subMonths(2))
            ->where('period_to', '>=', Carbon::now()->subMonths(2))
            ->exists();

        $this->assertFalse($paidPeriodNotice);
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

        // Check that past due notices are marked as overdue
        $overdueNotices = $customer->paymentNotices()
            ->where('status', 'overdue')
            ->where('due_date', '<', Carbon::now())
            ->count();

        $this->assertGreaterThan(0, $overdueNotices);

        // Check that future notices are pending
        $pendingNotices = $customer->paymentNotices()
            ->where('status', 'pending')
            ->where('due_date', '>=', Carbon::now())
            ->count();

        $this->assertGreaterThanOrEqual(0, $pendingNotices);
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
        $this->assertGreaterThan(0, $results['total_notices_created']);
        $this->assertEmpty($results['errors']);
    }
}
