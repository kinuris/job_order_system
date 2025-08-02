<x-layouts.app title="Payment Management">
    <div class="space-y-4 sm:space-y-6 px-2 sm:px-0">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">Payment Management</h1>
                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400">Monitor customer payments and billing</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2">
                <a href="{{ route('admin.payments.create') }}" 
                   class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Record Payment
                </a>
                <form method="POST" action="{{ route('admin.payments.generate-notices') }}" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Generate Notices
                    </button>
                </form>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 dark:bg-red-900/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Overdue</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $stats['overdue_notices'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Due This Week</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $stats['due_this_week'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Unpaid</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">₱{{ number_format($stats['total_unpaid'], 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Monthly Collected</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">₱{{ number_format($stats['monthly_collected'], 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-3 sm:p-4 space-y-3 sm:space-y-4">
            <form method="GET" action="{{ route('admin.payments.index') }}" class="space-y-4">
                <!-- First Row: Status, Customer Search, Plan Filter -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select name="status" id="status" class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>

                    <div>
                        <label for="customer_search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Customer Search</label>
                        <input type="text" name="customer_search" id="customer_search" value="{{ request('customer_search') }}" placeholder="Search by name, email, or phone..." class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base">
                    </div>

                    <div>
                        <label for="plan_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Plan Filter</label>
                        <select name="plan_filter" id="plan_filter" class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base">
                            <option value="">All Plans</option>
                            @foreach(\App\Models\Plan::all() as $plan)
                                <option value="{{ $plan->id }}" {{ request('plan_filter') == $plan->id ? 'selected' : '' }}>{{ $plan->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Second Row: Date Range Filters -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="due_date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Due Date From</label>
                        <input type="date" name="due_date_from" id="due_date_from" value="{{ request('due_date_from') }}" class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base">
                    </div>

                    <div>
                        <label for="due_date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Due Date To</label>
                        <input type="date" name="due_date_to" id="due_date_to" value="{{ request('due_date_to') }}" class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base">
                    </div>

                    <div>
                        <label for="amount_min" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Min Amount</label>
                        <input type="number" name="amount_min" id="amount_min" value="{{ request('amount_min') }}" placeholder="0.00" step="0.01" class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base">
                    </div>

                    <div>
                        <label for="amount_max" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Max Amount</label>
                        <input type="number" name="amount_max" id="amount_max" value="{{ request('amount_max') }}" placeholder="0.00" step="0.01" class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base">
                    </div>
                </div>

                <!-- Third Row: Checkboxes and Buttons -->
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                    <div class="flex flex-wrap items-center gap-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="overdue_only" value="1" {{ request('overdue_only') ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Overdue only</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="has_notices" value="1" {{ request('has_notices') ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Has notices</span>
                        </label>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-2">
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Apply Filters
                        </button>
                        <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Payment Notices Table/Cards --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
            @if($notices->count() > 0)
                {{-- Sorting Controls --}}
                <div class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 px-4 py-3">
                    <form method="GET" action="{{ route('admin.payments.index') }}" class="flex flex-col sm:flex-row gap-3 items-start sm:items-end">
                        {{-- Preserve all existing filter parameters --}}
                        @foreach(request()->except(['sort_by', 'sort_direction']) as $key => $value)
                            @if(is_array($value))
                                @foreach($value as $item)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        
                        <div class="flex flex-col sm:flex-row gap-3 flex-1">
                            <div class="flex-1">
                                <label for="sort_by" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Sort By</label>
                                <select name="sort_by" id="sort_by" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <option value="customer_name" {{ request('sort_by', 'customer_name') === 'customer_name' ? 'selected' : '' }}>Customer Name</option>
                                    <option value="unpaid_months" {{ request('sort_by') === 'unpaid_months' ? 'selected' : '' }}>Unpaid Months</option>
                                    <option value="due_date" {{ request('sort_by') === 'due_date' ? 'selected' : '' }}>Due Date</option>
                                    <option value="amount" {{ request('sort_by') === 'amount' ? 'selected' : '' }}>Amount</option>
                                </select>
                            </div>
                            
                            <div class="flex-1">
                                <label for="sort_direction" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Direction</label>
                                <select name="sort_direction" id="sort_direction" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <option value="asc" {{ request('sort_direction') === 'asc' ? 'selected' : '' }}>Ascending</option>
                                    <option value="desc" {{ request('sort_direction') === 'desc' ? 'selected' : '' }}>Descending</option>
                                </select>
                            </div>
                        </div>
                        
                        <button type="submit" class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-medium text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"></path>
                            </svg>
                            Sort
                        </button>
                    </form>
                </div>

                {{-- Mobile Cards View (Hidden on Desktop) --}}
                <div class="block lg:hidden space-y-3 p-4">
                    @foreach($notices as $notice)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                            {{-- Customer Info Header --}}
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                        {{ $notice->customer->full_name }}
                                    </h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                        {{ $notice->customer->email }}
                                    </p>
                                </div>
                                @php
                                    $customerFullyPaid = $notice->customer->isFullyPaid();
                                    
                                    // Determine display status based on customer's overall payment status
                                    if ($customerFullyPaid) {
                                        $displayStatus = 'paid';
                                        $statusLabel = 'Paid';
                                    } elseif ($notice->status === 'overdue') {
                                        $displayStatus = 'overdue';
                                        $statusLabel = 'Overdue';
                                    } else {
                                        $displayStatus = 'pending';
                                        $statusLabel = 'Pending';
                                    }
                                @endphp
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @if($displayStatus === 'paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif($displayStatus === 'overdue') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 @endif">
                                    {{ $statusLabel }}
                                </span>
                            </div>

                            {{-- Payment Details Grid --}}
                            <div class="grid grid-cols-2 gap-3 mb-3 text-xs">
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Plan:</span>
                                    <div class="font-medium text-gray-900 dark:text-gray-100">{{ $notice->plan->name }}</div>
                                </div>
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Amount Due:</span>
                                    <div class="font-medium text-gray-900 dark:text-gray-100">{{ $notice->formatted_amount_due }}</div>
                                </div>
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Due Date:</span>
                                    <div class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $notice->due_date->format('M j, Y') }}
                                        @if($notice->days_overdue > 0)
                                            <span class="text-red-600 dark:text-red-400">({{ $notice->days_overdue }}d overdue)</span>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Unpaid Months:</span>
                                    <div class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $customerUnpaidCounts[$notice->customer_id] ?? 0 }} months
                                    </div>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex flex-col sm:flex-row gap-2">
                                <a href="{{ route('admin.payments.customer', $notice->customer) }}" 
                                   class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-indigo-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View History
                                </a>
                                @if(($customerUnpaidCounts[$notice->customer_id] ?? 0) > 0)
                                    <a href="{{ route('admin.payments.create', ['customer_id' => $notice->customer->id]) }}" 
                                       class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-green-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                        <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Record Payment
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Desktop Table View (Hidden on Mobile) --}}
                <div class="hidden lg:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'customer_name', 'sort_direction' => request('sort_by', 'customer_name') === 'customer_name' && request('sort_direction', 'asc') === 'asc' ? 'desc' : 'asc']) }}" 
                                       class="group inline-flex items-center hover:text-gray-700 dark:hover:text-gray-300">
                                        Customer
                                        @if(request('sort_by', 'customer_name') === 'customer_name')
                                            @if(request('sort_direction', 'asc') === 'asc')
                                                <svg class="ml-1 w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/>
                                                </svg>
                                            @else
                                                <svg class="ml-1 w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                            @endif
                                        @else
                                            <svg class="ml-1 w-3 h-3 opacity-0 group-hover:opacity-50" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Plan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'due_date', 'sort_direction' => request('sort_by') === 'due_date' && request('sort_direction') === 'asc' ? 'desc' : 'asc']) }}" 
                                       class="group inline-flex items-center hover:text-gray-700 dark:hover:text-gray-300">
                                        Due Date
                                        @if(request('sort_by') === 'due_date')
                                            @if(request('sort_direction') === 'asc')
                                                <svg class="ml-1 w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/>
                                                </svg>
                                            @else
                                                <svg class="ml-1 w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                            @endif
                                        @else
                                            <svg class="ml-1 w-3 h-3 opacity-0 group-hover:opacity-50" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'amount', 'sort_direction' => request('sort_by') === 'amount' && request('sort_direction') === 'asc' ? 'desc' : 'asc']) }}" 
                                       class="group inline-flex items-center hover:text-gray-700 dark:hover:text-gray-300">
                                        Amount
                                        @if(request('sort_by') === 'amount')
                                            @if(request('sort_direction') === 'asc')
                                                <svg class="ml-1 w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/>
                                                </svg>
                                            @else
                                                <svg class="ml-1 w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                            @endif
                                        @else
                                            <svg class="ml-1 w-3 h-3 opacity-0 group-hover:opacity-50" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'unpaid_months', 'sort_direction' => request('sort_by') === 'unpaid_months' && request('sort_direction') === 'asc' ? 'desc' : 'asc']) }}" 
                                       class="group inline-flex items-center hover:text-gray-700 dark:hover:text-gray-300">
                                        Unpaid Months
                                        @if(request('sort_by') === 'unpaid_months')
                                            @if(request('sort_direction') === 'asc')
                                                <svg class="ml-1 w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/>
                                                </svg>
                                            @else
                                                <svg class="ml-1 w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                            @endif
                                        @else
                                            <svg class="ml-1 w-3 h-3 opacity-0 group-hover:opacity-50" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($notices as $notice)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $notice->customer->full_name }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $notice->customer->email }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $notice->plan->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">
                                            {{ $notice->due_date->format('M j, Y') }}
                                        </div>
                                        @if($notice->days_overdue > 0)
                                            <div class="text-xs text-red-600 dark:text-red-400">
                                                {{ $notice->days_overdue }} days overdue
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $notice->formatted_amount_due }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $customerUnpaidCounts[$notice->customer_id] ?? 0 }} 
                                            <span class="text-xs text-gray-500 dark:text-gray-400">months</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $customerFullyPaid = $notice->customer->isFullyPaid();
                                            
                                            // Determine display status based on customer's overall payment status
                                            if ($customerFullyPaid) {
                                                $displayStatus = 'paid';
                                                $statusLabel = 'Paid';
                                            } elseif ($notice->status === 'overdue') {
                                                $displayStatus = 'overdue';
                                                $statusLabel = 'Overdue';
                                            } else {
                                                $displayStatus = 'pending';
                                                $statusLabel = 'Pending';
                                            }
                                        @endphp
                                        
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($displayStatus === 'paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @elseif($displayStatus === 'overdue') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                            @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 @endif">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.payments.customer', $notice->customer) }}" 
                                               class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                View
                                            </a>
                                            @if(($customerUnpaidCounts[$notice->customer_id] ?? 0) > 0)
                                                <a href="{{ route('admin.payments.create', ['customer_id' => $notice->customer->id]) }}" 
                                                   class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                                    Record Payment
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($notices->hasPages())
                    <div class="px-4 sm:px-6 py-3 border-t border-gray-200 dark:border-gray-700">
                        {{ $notices->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <div class="p-6 sm:p-12 text-center">
                    <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm sm:text-base font-medium text-gray-900 dark:text-gray-100">No payment notices</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Generate notices to track customer payments.</p>
                </div>
            @endif
        </div>

        {{-- Mobile Floating Action Button --}}
        <div class="fixed bottom-6 right-6 lg:hidden z-50">
            <a href="{{ route('admin.payments.create') }}" 
               class="inline-flex items-center justify-center w-14 h-14 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 rounded-full shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105 active:scale-95">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span class="sr-only">Record Payment</span>
            </a>
        </div>
    </div>
</x-layouts.app>
