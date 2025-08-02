<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Plan;
use App\Models\PaymentNotice;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClearPaymentNoticesTest extends TestCase
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
    public function it_can_clear_all_payment_notices()
    {
        // Create some payment notices
        $customers = Customer::factory()->count(3)->create([
            'plan_id' => $this->plan->id,
            'plan_status' => 'active'
        ]);

        foreach ($customers as $customer) {
            PaymentNotice::factory()->count(2)->create([
                'customer_id' => $customer->id,
                'plan_id' => $this->plan->id,
                'status' => 'pending'
            ]);
        }

        $initialCount = PaymentNotice::count();
        $this->assertEquals(6, $initialCount);

        // Clear all notices
        $result = $this->paymentService->clearPaymentNotices();

        $this->assertEquals(6, $result['deleted_count']);
        $this->assertEquals(0, PaymentNotice::count());
    }

    /** @test */
    public function it_can_clear_notices_by_status()
    {
        $customer = Customer::factory()->create([
            'plan_id' => $this->plan->id,
            'plan_status' => 'active'
        ]);

        // Create notices with different statuses
        PaymentNotice::factory()->create([
            'customer_id' => $customer->id,
            'plan_id' => $this->plan->id,
            'status' => 'pending'
        ]);

        PaymentNotice::factory()->create([
            'customer_id' => $customer->id,
            'plan_id' => $this->plan->id,
            'status' => 'overdue'
        ]);

        PaymentNotice::factory()->create([
            'customer_id' => $customer->id,
            'plan_id' => $this->plan->id,
            'status' => 'paid'
        ]);

        // Clear only pending notices
        $result = $this->paymentService->clearPaymentNotices(['status' => 'pending']);

        $this->assertEquals(1, $result['deleted_count']);
        $this->assertEquals(2, PaymentNotice::count());
        $this->assertEquals(0, PaymentNotice::where('status', 'pending')->count());
    }

    /** @test */
    public function it_can_clear_notices_for_specific_customer()
    {
        $customer1 = Customer::factory()->create([
            'plan_id' => $this->plan->id,
            'plan_status' => 'active'
        ]);

        $customer2 = Customer::factory()->create([
            'plan_id' => $this->plan->id,
            'plan_status' => 'active'
        ]);

        // Create notices for both customers
        PaymentNotice::factory()->count(2)->create([
            'customer_id' => $customer1->id,
            'plan_id' => $this->plan->id,
        ]);

        PaymentNotice::factory()->count(3)->create([
            'customer_id' => $customer2->id,
            'plan_id' => $this->plan->id,
        ]);

        // Clear notices for customer1 only
        $result = $this->paymentService->clearPaymentNotices(['customer_id' => $customer1->id]);

        $this->assertEquals(2, $result['deleted_count']);
        $this->assertEquals(3, PaymentNotice::count());
        $this->assertEquals(0, PaymentNotice::where('customer_id', $customer1->id)->count());
        $this->assertEquals(3, PaymentNotice::where('customer_id', $customer2->id)->count());
    }

    /** @test */
    public function it_can_clear_notices_older_than_date()
    {
        $customer = Customer::factory()->create([
            'plan_id' => $this->plan->id,
            'plan_status' => 'active'
        ]);

        // Create old notices
        PaymentNotice::factory()->create([
            'customer_id' => $customer->id,
            'plan_id' => $this->plan->id,
            'due_date' => Carbon::now()->subMonths(2),
            'status' => 'pending'
        ]);

        PaymentNotice::factory()->create([
            'customer_id' => $customer->id,
            'plan_id' => $this->plan->id,
            'due_date' => Carbon::now()->subMonth(),
            'status' => 'overdue'
        ]);

        // Create recent notice
        PaymentNotice::factory()->create([
            'customer_id' => $customer->id,
            'plan_id' => $this->plan->id,
            'due_date' => Carbon::now()->addDays(10),
            'status' => 'pending'
        ]);

        $cutoffDate = Carbon::now()->subDays(15);
        $deletedCount = $this->paymentService->clearNoticesOlderThan($cutoffDate);

        $this->assertEquals(2, $deletedCount);
        $this->assertEquals(1, PaymentNotice::count());
    }

    /** @test */
    public function it_can_clear_unpaid_notices_for_customer()
    {
        $customer = Customer::factory()->create([
            'plan_id' => $this->plan->id,
            'plan_status' => 'active'
        ]);

        // Create notices with different statuses
        PaymentNotice::factory()->create([
            'customer_id' => $customer->id,
            'plan_id' => $this->plan->id,
            'status' => 'pending'
        ]);

        PaymentNotice::factory()->create([
            'customer_id' => $customer->id,
            'plan_id' => $this->plan->id,
            'status' => 'overdue'
        ]);

        PaymentNotice::factory()->create([
            'customer_id' => $customer->id,
            'plan_id' => $this->plan->id,
            'status' => 'paid'
        ]);

        $deletedCount = $this->paymentService->clearUnpaidNoticesForCustomer($customer);

        $this->assertEquals(2, $deletedCount);
        $this->assertEquals(1, PaymentNotice::count());
        $this->assertEquals('paid', PaymentNotice::first()->status);
    }

    /** @test */
    public function it_handles_clearing_when_no_notices_exist()
    {
        $result = $this->paymentService->clearPaymentNotices();

        $this->assertEquals(0, $result['deleted_count']);
        $this->assertStringContainsString('No payment notices found', $result['message']);
    }

    /** @test */
    public function it_can_clear_notices_with_multiple_criteria()
    {
        $customer1 = Customer::factory()->create(['plan_id' => $this->plan->id]);
        $customer2 = Customer::factory()->create(['plan_id' => $this->plan->id]);

        // Create notices
        PaymentNotice::factory()->create([
            'customer_id' => $customer1->id,
            'plan_id' => $this->plan->id,
            'status' => 'pending',
            'due_date' => Carbon::now()->subDays(10)
        ]);

        PaymentNotice::factory()->create([
            'customer_id' => $customer1->id,
            'plan_id' => $this->plan->id,
            'status' => 'overdue',
            'due_date' => Carbon::now()->subDays(10)
        ]);

        PaymentNotice::factory()->create([
            'customer_id' => $customer2->id,
            'plan_id' => $this->plan->id,
            'status' => 'pending',
            'due_date' => Carbon::now()->subDays(10)
        ]);

        // Clear pending notices for customer1 only
        $result = $this->paymentService->clearPaymentNotices([
            'customer_id' => $customer1->id,
            'status' => 'pending'
        ]);

        $this->assertEquals(1, $result['deleted_count']);
        $this->assertEquals(2, PaymentNotice::count());
    }
}
