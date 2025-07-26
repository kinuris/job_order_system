<x-layouts.app title="Payment Details">
    <div class="space-y-4 sm:space-y-6 px-2 sm:px-0">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">Payment Details</h1>
                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400">Payment #{{ $payment->id }}</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2">
                <a href="{{ route('admin.payments.customer', $payment->customer) }}" 
                   class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    View Customer History
                </a>
                <a href="{{ route('admin.payments.index') }}" 
                   class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Back to Payments
                </a>
            </div>
        </div>

        {{-- Payment Information --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Payment Information</h3>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-4">Customer Details</h4>
                        <dl class="space-y-2">
                            <div>
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Customer</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $payment->customer->full_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Email</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $payment->customer->email ?: 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Plan</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $payment->plan->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Plan Rate (at time of payment)</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $payment->formatted_plan_rate }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-4">Payment Details</h4>
                        <dl class="space-y-2">
                            <div>
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Amount Paid</dt>
                                <dd class="text-lg font-bold text-green-600 dark:text-green-400">{{ $payment->formatted_amount }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Payment Date</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $payment->payment_date->format('F j, Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Payment Method</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $payment->payment_method_label }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Status</dt>
                                <dd>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($payment->status === 'confirmed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                        @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                        {{ $payment->status_label }}
                                    </span>
                                </dd>
                            </div>
                            @if($payment->reference_number)
                                <div>
                                    <dt class="text-sm text-gray-500 dark:text-gray-400">Reference Number</dt>
                                    <dd class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $payment->reference_number }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                {{-- Coverage Period --}}
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-4">Coverage Period</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Period From</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $payment->period_from->format('F j, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Period To</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $payment->period_to->format('F j, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Duration</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $payment->period_months }} {{ Str::plural('month', $payment->period_months) }}</dd>
                        </div>
                    </div>
                    
                    @if($payment->is_advance_payment)
                        <div class="mt-4 p-3 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-purple-600 dark:text-purple-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm text-purple-700 dark:text-purple-300 font-medium">Advance Payment</span>
                            </div>
                            <p class="text-sm text-purple-600 dark:text-purple-400 mt-1">This payment covers future billing periods.</p>
                        </div>
                    @endif
                </div>

                {{-- Notes --}}
                @if($payment->notes)
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">Notes</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $payment->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Customer Payment Summary --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Customer Payment Summary</h3>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">₱{{ number_format($summary['total_paid'], 2) }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Total Paid</div>
                    </div>
                    
                    @if($summary['overdue_amount'] > 0)
                        <div class="text-center">
                            <div class="text-2xl font-bold text-red-600 dark:text-red-400">₱{{ number_format($summary['overdue_amount'], 2) }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Overdue ({{ $summary['overdue_count'] }} notices)</div>
                        </div>
                    @endif
                    
                    <div class="text-center">
                        <div class="text-2xl font-bold {{ $summary['balance'] > 0 ? 'text-yellow-600 dark:text-yellow-400' : ($summary['balance'] < 0 ? 'text-purple-600 dark:text-purple-400' : 'text-green-600 dark:text-green-400') }}">
                            ₱{{ number_format(abs($summary['balance']), 2) }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $summary['balance'] > 0 ? 'Owing' : ($summary['balance'] < 0 ? 'Advance' : 'Current') }}
                        </div>
                    </div>
                    
                    @if($summary['next_due_date'])
                        <div class="text-center">
                            <div class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $summary['next_due_date']->format('M j, Y') }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Next Due Date</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
