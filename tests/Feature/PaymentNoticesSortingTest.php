<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Plan;
use App\Models\PaymentNotice;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentNoticesSortingTest extends TestCase
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
    public function it_can_sort_payment_notices_by_customer_name()
    {
        // Create customers with different names
        $customer1 = Customer::factory()->create([
            'first_name' => 'Charlie',
            'last_name' => 'Brown',
            'plan_id' => $this->plan->id,
            'plan_status' => 'active'
        ]);

        $customer2 = Customer::factory()->create([
            'first_name' => 'Alice',
            'last_name' => 'Johnson',
            'plan_id' => $this->plan->id,
            'plan_status' => 'active'
        ]);

        $customer3 = Customer::factory()->create([
            'first_name' => 'Bob',
            'last_name' => 'Smith',
            'plan_id' => $this->plan->id,
            'plan_status' => 'active'
        ]);

        // Create payment notices for each customer
        PaymentNotice::factory()->create([
            'customer_id' => $customer1->id,
            'plan_id' => $this->plan->id,
            'status' => 'pending'
        ]);

        PaymentNotice::factory()->create([
            'customer_id' => $customer2->id,
            'plan_id' => $this->plan->id,
            'status' => 'pending'
        ]);

        PaymentNotice::factory()->create([
            'customer_id' => $customer3->id,
            'plan_id' => $this->plan->id,
            'status' => 'pending'
        ]);

        // Sort by name ascending
        $noticesAsc = $this->paymentService->getPaymentNoticesWithSorting([
            'sort_by' => 'name',
            'sort_direction' => 'asc'
        ]);

        $expectedOrder = ['Alice Johnson', 'Bob Smith', 'Charlie Brown'];
        $actualOrder = $noticesAsc->pluck('customer.full_name')->toArray();

        $this->assertEquals($expectedOrder, $actualOrder);

        // Sort by name descending
        $noticesDesc = $this->paymentService->getPaymentNoticesWithSorting([
            'sort_by' => 'name',
            'sort_direction' => 'desc'
        ]);

        $expectedOrderDesc = ['Charlie Brown', 'Bob Smith', 'Alice Johnson'];
        $actualOrderDesc = $noticesDesc->pluck('customer.full_name')->toArray();

        $this->assertEquals($expectedOrderDesc, $actualOrderDesc);
    }

    /** @test */
    public function it_can_sort_customers_by_unpaid_months()
    {
        // Create customers with different installation dates (different unpaid months)
        $customer1 = Customer::factory()->create([
            'first_name' => 'Customer',
            'last_name' => 'One',
            'plan_id' => $this->plan->id,
            'plan_installed_at' => Carbon::now()->subMonths(1), // 1 month
            'plan_status' => 'active'
        ]);

        $customer2 = Customer::factory()->create([
            'first_name' => 'Customer',
            'last_name' => 'Two',
            'plan_id' => $this->plan->id,
            'plan_installed_at' => Carbon::now()->subMonths(5), // 5 months
            'plan_status' => 'active'
        ]);

        $customer3 = Customer::factory()->create([
            'first_name' => 'Customer',
            'last_name' => 'Three',
            'plan_id' => $this->plan->id,
            'plan_installed_at' => Carbon::now()->subMonths(3), // 3 months
            'plan_status' => 'active'
        ]);

        // Create payment notices for each customer
        foreach ([$customer1, $customer2, $customer3] as $customer) {
            PaymentNotice::factory()->create([
                'customer_id' => $customer->id,
                'plan_id' => $this->plan->id,
                'status' => 'pending'
            ]);
        }

        // Sort by unpaid months ascending
        $customersAsc = $this->paymentService->getCustomersWithNoticesSorted([
            'sort_by' => 'unpaid_months',
            'sort_direction' => 'asc'
        ]);

        $unpaidMonthsAsc = $customersAsc->map(fn($c) => $c->getUnpaidMonths())->toArray();
        $this->assertEquals([2, 4, 6], $unpaidMonthsAsc); // Approximately 1, 3, 5 months

        // Sort by unpaid months descending
        $customersDesc = $this->paymentService->getCustomersWithNoticesSorted([
            'sort_by' => 'unpaid_months',
            'sort_direction' => 'desc'
        ]);

        $unpaidMonthsDesc = $customersDesc->map(fn($c) => $c->getUnpaidMonths())->toArray();
        $this->assertEquals([6, 4, 2], $unpaidMonthsDesc); // Reverse order
    }

    /** @test */
    public function it_can_filter_notices_by_status()
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

        // Filter by pending status only
        $pendingNotices = $this->paymentService->getPaymentNoticesWithSorting([
            'status' => 'pending'
        ]);

        $this->assertEquals(1, $pendingNotices->count());
        $this->assertEquals('pending', $pendingNotices->first()->status);

        // Filter by multiple statuses
        $unpaidNotices = $this->paymentService->getPaymentNoticesWithSorting([
            'status' => ['pending', 'overdue']
        ]);

        $this->assertEquals(2, $unpaidNotices->count());
    }

    /** @test */
    public function it_can_generate_payment_notices_summary()
    {
        // Create customers with varying unpaid amounts
        $customer1 = Customer::factory()->create([
            'first_name' => 'Alice',
            'plan_id' => $this->plan->id,
            'plan_status' => 'active'
        ]);

        $customer2 = Customer::factory()->create([
            'first_name' => 'Bob',
            'plan_id' => $this->plan->id,
            'plan_status' => 'active'
        ]);

        // Create multiple notices for customer1
        PaymentNotice::factory()->count(2)->create([
            'customer_id' => $customer1->id,
            'plan_id' => $this->plan->id,
            'status' => 'pending',
            'amount_due' => 1500
        ]);

        // Create one notice for customer2
        PaymentNotice::factory()->create([
            'customer_id' => $customer2->id,
            'plan_id' => $this->plan->id,
            'status' => 'overdue',
            'amount_due' => 1500
        ]);

        $summary = $this->paymentService->getPaymentNoticesSummary([
            'sort_by' => 'name',
            'sort_direction' => 'asc'
        ]);

        $this->assertEquals(2, $summary['total_customers']);
        $this->assertEquals(3, $summary['total_notices']);
        $this->assertEquals(4500, $summary['total_amount']);
        
        // Check if customers are sorted by name
        $this->assertEquals('Alice', $summary['customers'][0]['name']);
        $this->assertEquals('Bob', $summary['customers'][1]['name']);
    }

    /** @test */
    public function it_can_group_notices_summary()
    {
        $customer1 = Customer::factory()->create([
            'plan_id' => $this->plan->id,
            'plan_installed_at' => Carbon::now()->subMonths(1),
            'plan_status' => 'active'
        ]);

        $customer2 = Customer::factory()->create([
            'plan_id' => $this->plan->id,
            'plan_installed_at' => Carbon::now()->subMonths(5),
            'plan_status' => 'active'
        ]);

        PaymentNotice::factory()->create([
            'customer_id' => $customer1->id,
            'plan_id' => $this->plan->id,
            'status' => 'pending'
        ]);

        PaymentNotice::factory()->create([
            'customer_id' => $customer2->id,
            'plan_id' => $this->plan->id,
            'status' => 'pending'
        ]);

        $summary = $this->paymentService->getPaymentNoticesSummary([
            'group_by' => 'unpaid_months'
        ]);

        $this->assertArrayHasKey('grouped', $summary);
        $this->assertIsArray($summary['grouped']);
    }

    /** @test */
    public function it_can_filter_notices_by_customer()
    {
        $customer1 = Customer::factory()->create(['plan_id' => $this->plan->id]);
        $customer2 = Customer::factory()->create(['plan_id' => $this->plan->id]);

        PaymentNotice::factory()->create([
            'customer_id' => $customer1->id,
            'plan_id' => $this->plan->id
        ]);

        PaymentNotice::factory()->create([
            'customer_id' => $customer2->id,
            'plan_id' => $this->plan->id
        ]);

        $customer1Notices = $this->paymentService->getPaymentNoticesWithSorting([
            'customer_id' => $customer1->id
        ]);

        $this->assertEquals(1, $customer1Notices->count());
        $this->assertEquals($customer1->id, $customer1Notices->first()->customer_id);
    }
}
