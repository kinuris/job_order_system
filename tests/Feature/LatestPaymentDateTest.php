<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\CustomerPayment;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LatestPaymentDateTest extends TestCase
{
    use RefreshDatabase;

    protected Plan $plan;

    protected function setUp(): void
    {
        parent::setUp();
        $this->plan = Plan::factory()->create([
            'name' => 'Test Plan',
            'monthly_rate' => 1500.00
        ]);
    }

    /** @test */
    public function customer_can_get_latest_payment_date()
    {
        $customer = Customer::factory()->create([
            'plan_id' => $this->plan->id,
            'plan_installed_at' => Carbon::now()->subMonths(3),
            'plan_status' => 'active'
        ]);

        // Create multiple payments
        CustomerPayment::create([
            'customer_id' => $customer->id,
            'plan_id' => $this->plan->id,
            'amount' => 1500.00,
            'plan_rate' => 1500.00,
            'payment_date' => Carbon::now()->subMonths(2),
            'period_from' => Carbon::now()->subMonths(3),
            'period_to' => Carbon::now()->subMonths(2),
            'payment_method' => 'cash',
            'status' => 'confirmed'
        ]);

        $latestPayment = CustomerPayment::create([
            'customer_id' => $customer->id,
            'plan_id' => $this->plan->id,
            'amount' => 1500.00,
            'plan_rate' => 1500.00,
            'payment_date' => Carbon::now()->subMonth(),
            'period_from' => Carbon::now()->subMonths(2),
            'period_to' => Carbon::now()->subMonth(),
            'payment_method' => 'cash',
            'status' => 'confirmed'
        ]);

        CustomerPayment::create([
            'customer_id' => $customer->id,
            'plan_id' => $this->plan->id,
            'amount' => 1500.00,
            'plan_rate' => 1500.00,
            'payment_date' => Carbon::now()->subMonths(3),
            'period_from' => Carbon::now()->subMonths(4),
            'period_to' => Carbon::now()->subMonths(3),
            'payment_method' => 'cash',
            'status' => 'confirmed'
        ]);

        $latestPaymentDate = $customer->getLatestPaymentDate();

        $this->assertNotNull($latestPaymentDate);
        $this->assertEquals(
            $latestPayment->payment_date->format('Y-m-d'),
            $latestPaymentDate->format('Y-m-d')
        );
    }

    /** @test */
    public function customer_returns_null_when_no_payments()
    {
        $customer = Customer::factory()->create([
            'plan_id' => $this->plan->id,
            'plan_installed_at' => Carbon::now()->subMonth(),
            'plan_status' => 'active'
        ]);

        $latestPaymentDate = $customer->getLatestPaymentDate();

        $this->assertNull($latestPaymentDate);
    }

    /** @test */
    public function customer_ignores_unconfirmed_payments()
    {
        $customer = Customer::factory()->create([
            'plan_id' => $this->plan->id,
            'plan_installed_at' => Carbon::now()->subMonths(3),
            'plan_status' => 'active'
        ]);

        // Create confirmed payment
        $confirmedPayment = CustomerPayment::create([
            'customer_id' => $customer->id,
            'plan_id' => $this->plan->id,
            'amount' => 1500.00,
            'plan_rate' => 1500.00,
            'payment_date' => Carbon::now()->subMonths(2),
            'period_from' => Carbon::now()->subMonths(3),
            'period_to' => Carbon::now()->subMonths(2),
            'payment_method' => 'cash',
            'status' => 'confirmed'
        ]);

        // Create pending payment with more recent date
        CustomerPayment::create([
            'customer_id' => $customer->id,
            'plan_id' => $this->plan->id,
            'amount' => 1500.00,
            'plan_rate' => 1500.00,
            'payment_date' => Carbon::now()->subMonth(),
            'period_from' => Carbon::now()->subMonths(2),
            'period_to' => Carbon::now()->subMonth(),
            'payment_method' => 'cash',
            'status' => 'pending'
        ]);

        $latestPaymentDate = $customer->getLatestPaymentDate();

        $this->assertNotNull($latestPaymentDate);
        $this->assertEquals(
            $confirmedPayment->payment_date->format('Y-m-d'),
            $latestPaymentDate->format('Y-m-d')
        );
    }
}
