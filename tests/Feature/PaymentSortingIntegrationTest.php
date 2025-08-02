<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\PaymentNotice;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentSortingIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    /** @test */
    public function admin_can_access_payment_notices_page_with_sorting()
    {
        // Create test data
        $plan = Plan::factory()->create(['name' => 'Basic Plan', 'monthly_rate' => 1500]);
        
        $customerA = Customer::factory()->create([
            'first_name' => 'Alice',
            'last_name' => 'Smith',
            'plan_id' => $plan->id,
        ]);
        
        $customerB = Customer::factory()->create([
            'first_name' => 'Bob',
            'last_name' => 'Jones',
            'plan_id' => $plan->id,
        ]);

        // Create payment notices
        PaymentNotice::factory()->create([
            'customer_id' => $customerA->id,
            'plan_id' => $plan->id,
            'amount_due' => 1500,
            'due_date' => now()->addDays(5),
            'status' => 'pending'
        ]);
        
        PaymentNotice::factory()->create([
            'customer_id' => $customerB->id,
            'plan_id' => $plan->id,
            'amount_due' => 1500,
            'due_date' => now()->addDays(10),
            'status' => 'pending'
        ]);

        // Test default view (no sorting)
        $response = $this->actingAs($this->admin)
            ->get(route('admin.payments.index'));
        
        $response->assertOk();
        $response->assertSee('Alice Smith');
        $response->assertSee('Bob Jones');

        // Test sorting by customer name ascending
        $response = $this->actingAs($this->admin)
            ->get(route('admin.payments.index', [
                'sort_by' => 'customer_name',
                'sort_direction' => 'asc'
            ]));
        
        $response->assertOk();
        $response->assertSee('Alice Smith');
        $response->assertSee('Bob Jones');

        // Test sorting by customer name descending
        $response = $this->actingAs($this->admin)
            ->get(route('admin.payments.index', [
                'sort_by' => 'customer_name',
                'sort_direction' => 'desc'
            ]));
        
        $response->assertOk();
        $response->assertSee('Bob Jones');
        $response->assertSee('Alice Smith');
    }

    /** @test */
    public function admin_can_sort_by_unpaid_months()
    {
        $plan = Plan::factory()->create(['monthly_rate' => 1500]);
        
        // Customer with 1 unpaid notice
        $customer1 = Customer::factory()->create([
            'first_name' => 'Customer',
            'last_name' => 'One',
            'plan_id' => $plan->id,
        ]);
        
        // Customer with 2 unpaid notices
        $customer2 = Customer::factory()->create([
            'first_name' => 'Customer',
            'last_name' => 'Two',
            'plan_id' => $plan->id,
        ]);

        // Create notices for customer 1 (1 unpaid)
        PaymentNotice::factory()->create([
            'customer_id' => $customer1->id,
            'plan_id' => $plan->id,
            'status' => 'pending'
        ]);
        
        // Create notices for customer 2 (2 unpaid)
        PaymentNotice::factory()->create([
            'customer_id' => $customer2->id,
            'plan_id' => $plan->id,
            'status' => 'pending'
        ]);
        
        PaymentNotice::factory()->create([
            'customer_id' => $customer2->id,
            'plan_id' => $plan->id,
            'status' => 'overdue'
        ]);

        // Test sorting by unpaid months descending (most unpaid first)
        $response = $this->actingAs($this->admin)
            ->get(route('admin.payments.index', [
                'sort_by' => 'unpaid_months',
                'sort_direction' => 'desc'
            ]));
        
        $response->assertOk();
        $response->assertSee('Customer Two');
        $response->assertSee('Customer One');
    }

    /** @test */
    public function sorting_dropdown_shows_current_selection()
    {
        // Create test data so sorting controls appear
        $plan = Plan::factory()->create(['monthly_rate' => 1500]);
        $customer = Customer::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'Customer',
            'plan_id' => $plan->id,
        ]);
        
        PaymentNotice::factory()->create([
            'customer_id' => $customer->id,
            'plan_id' => $plan->id,
            'status' => 'pending'
        ]);
        
        $response = $this->actingAs($this->admin)
            ->get(route('admin.payments.index', [
                'sort_by' => 'customer_name',
                'sort_direction' => 'desc'
            ]));
        
        $response->assertOk();
        $response->assertSee('option value="customer_name" selected', false);
        $response->assertSee('option value="desc" selected', false);
    }
}
