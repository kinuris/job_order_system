<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Plan;
use App\Models\PaymentNotice;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentNoticeDueDateTest extends TestCase
{
    use RefreshDatabase;

    protected PaymentService $paymentService;
    protected Plan $plan;

    protected function setUp(): void
    {
        parent::setUp();
        $this->paymentService = app(PaymentService::class);
        $this->plan = Plan::factory()->create([
            'name' => 'Test Plan',
            'monthly_rate' => 1500.00
        ]);
    }

    /** @test */
    public function payment_notices_preserve_installation_date_day()
    {
        // Create customer installed on the 15th of the month
        $installationDate = Carbon::create(2025, 1, 15);
        $customer = Customer::factory()->create([
            'plan_id' => $this->plan->id,
            'plan_installed_at' => $installationDate,
            'plan_status' => 'active'
        ]);

        // Generate payment notices
        $this->paymentService->recalculateFromInstallationDates();

        // Get the generated notices
        $notices = $customer->paymentNotices()->orderBy('due_date')->get();

        // Check that due dates maintain the 15th day
        foreach ($notices as $notice) {
            $this->assertEquals(15, $notice->due_date->day, 
                "Due date should be on the 15th, got {$notice->due_date->day} for date {$notice->due_date}");
        }
    }

    /** @test */
    public function payment_notices_handle_month_end_dates_correctly()
    {
        // Create customer installed on the 31st of January
        $installationDate = Carbon::create(2025, 1, 31);
        $customer = Customer::factory()->create([
            'plan_id' => $this->plan->id,
            'plan_installed_at' => $installationDate,
            'plan_status' => 'active'
        ]);

        // Generate payment notices
        $this->paymentService->recalculateFromInstallationDates();

        // Get the generated notices
        $notices = $customer->paymentNotices()->orderBy('due_date')->get();

        // Check that February notice uses the 28th (February has 28 days in 2025)
        $februaryNotice = $notices->where('due_date', '>=', '2025-02-01')
                                 ->where('due_date', '<', '2025-03-01')
                                 ->first();
        
        if ($februaryNotice) {
            $this->assertEquals(28, $februaryNotice->due_date->day, 
                "February notice should use 28th day (last day of Feb), got {$februaryNotice->due_date->day}");
        }

        // Check that March notice goes back to 31st
        $marchNotice = $notices->where('due_date', '>=', '2025-03-01')
                              ->where('due_date', '<', '2025-04-01')
                              ->first();
        
        if ($marchNotice) {
            $this->assertEquals(31, $marchNotice->due_date->day, 
                "March notice should use 31st day, got {$marchNotice->due_date->day}");
        }
    }

    /** @test */
    public function get_next_billing_date_preserves_installation_day()
    {
        // Create customer installed on the 20th
        $installationDate = Carbon::create(2025, 1, 20);
        $customer = Customer::factory()->create([
            'plan_id' => $this->plan->id,
            'plan_installed_at' => $installationDate,
            'plan_status' => 'active'
        ]);

        // First billing should be February 20th
        $nextBilling = $customer->getNextBillingDate();
        $this->assertEquals(20, $nextBilling->day);
        $this->assertEquals(2, $nextBilling->month);
        $this->assertEquals(2025, $nextBilling->year);
    }

    /** @test */
    public function create_payment_notice_uses_correct_due_date()
    {
        // Create customer installed on the 10th
        $installationDate = Carbon::create(2025, 1, 10);
        $customer = Customer::factory()->create([
            'plan_id' => $this->plan->id,
            'plan_installed_at' => $installationDate,
            'plan_status' => 'active'
        ]);

        // Create a payment notice
        $notice = $this->paymentService->createPaymentNotice($customer);

        // Due date should be February 10th
        $this->assertEquals(10, $notice->due_date->day);
        $this->assertEquals(2, $notice->due_date->month);
        $this->assertEquals(2025, $notice->due_date->year);
    }

    /** @test */
    public function update_notices_for_installation_date_change_creates_single_notice()
    {
        // Create customer
        $customer = Customer::factory()->create([
            'plan_id' => $this->plan->id,
            'plan_installed_at' => Carbon::create(2025, 1, 5),
            'plan_status' => 'active'
        ]);

        // Generate initial notices
        $this->paymentService->recalculateFromInstallationDates();
        
        // Create some paid notices by manually setting status
        $customer->paymentNotices()->take(2)->update(['status' => 'paid']);
        
        $initialCount = $customer->paymentNotices()->count();
        $paidCount = $customer->paymentNotices()->where('status', 'paid')->count();
        
        $this->assertGreaterThan(0, $initialCount);
        $this->assertEquals(2, $paidCount);

        // Change installation date to the 25th
        $customer->update(['plan_installed_at' => Carbon::create(2025, 1, 25)]);

        // Update notices for the new installation date
        $this->paymentService->updateNoticesForInstallationDateChange($customer);

        // Should now have only one notice (or none if already paid)
        $remainingNotices = $customer->paymentNotices()->count();
        $this->assertLessThanOrEqual(1, $remainingNotices);

        // If there is a notice, it should have due date on the 25th
        $notices = $customer->paymentNotices()->get();
        foreach($notices as $notice) {
            $this->assertEquals(25, $notice->due_date->day, 
                "Due date should be on the 25th after installation date change, got {$notice->due_date->day}");
        }
    }
}
