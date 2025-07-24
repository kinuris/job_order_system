<x-layouts.app title="Customers">
    <div class="space-y-4 sm:space-y-6 px-2 sm:px-0">
        {{-- Header - Mobile Optimized --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">Customers</h1>
                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400">Manage customer information and contact details</p>
            </div>
            {{-- Add Customer Button - Mobile First --}}
            <a href="{{ route('admin.customers.create') }}" 
               class="inline-flex items-center justify-center px-4 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 w-full sm:w-auto">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Customer
            </a>
        </div>

        {{-- Search and Filters - Mobile Optimized --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4 lg:p-6 space-y-4">
            <form method="GET" action="{{ route('admin.customers.index') }}" id="filter-form">
                {{-- Search Bar --}}
                <div class="relative">
                    <input 
                        type="text" 
                        name="search"
                        placeholder="Search customers..." 
                        value="{{ request('search') }}"
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                        id="customer-search"
                    >
                    <svg class="absolute left-3 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                {{-- Advanced Filters Toggleable Section --}}
                <div x-data="{ showFilters: {{ request()->hasAny(['plan_filter', 'status_filter', 'plan_type_filter', 'date_from', 'date_to', 'sort_by', 'sort_direction']) ? 'true' : 'false' }} }" class="space-y-4">
                    {{-- Toggle Button --}}
                    <button type="button" @click="showFilters = !showFilters" 
                            class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 font-medium">
                        <svg x-show="!showFilters" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                        <svg x-show="showFilters" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                        </svg>
                        Advanced Filters
                    </button>

                    {{-- Filter Grid --}}
                    <div x-show="showFilters" x-transition class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        {{-- Plan Filter --}}
                        <div>
                            <label for="plan_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Plan
                            </label>
                            <select name="plan_filter" id="plan_filter" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                    onchange="this.form.submit()">
                                <option value="">All Plans</option>
                                <option value="no_plan" {{ request('plan_filter') === 'no_plan' ? 'selected' : '' }}>No Plan</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" {{ request('plan_filter') == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }} ({{ $plan->formatted_monthly_rate }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Plan Status Filter --}}
                        <div>
                            <label for="status_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Plan Status
                            </label>
                            <select name="status_filter" id="status_filter" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                    onchange="this.form.submit()">
                                <option value="">All Statuses</option>
                                @foreach($planStatuses as $status => $label)
                                    <option value="{{ $status }}" {{ request('status_filter') === $status ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Plan Type Filter --}}
                        <div>
                            <label for="plan_type_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Plan Type
                            </label>
                            <select name="plan_type_filter" id="plan_type_filter" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                    onchange="this.form.submit()">
                                <option value="">All Types</option>
                                @foreach($planTypes as $type)
                                    <option value="{{ $type }}" {{ request('plan_type_filter') === $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Sort Options --}}
                        <div>
                            <label for="sort_by" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Sort By
                            </label>
                            <select name="sort_by" id="sort_by" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                    onchange="this.form.submit()">
                                <option value="created_at" {{ request('sort_by', 'created_at') === 'created_at' ? 'selected' : '' }}>Date Added</option>
                                <option value="first_name" {{ request('sort_by') === 'first_name' ? 'selected' : '' }}>First Name</option>
                                <option value="last_name" {{ request('sort_by') === 'last_name' ? 'selected' : '' }}>Last Name</option>
                                <option value="email" {{ request('sort_by') === 'email' ? 'selected' : '' }}>Email</option>
                                <option value="plan_installed_at" {{ request('sort_by') === 'plan_installed_at' ? 'selected' : '' }}>Installation Date</option>
                            </select>
                            <input type="hidden" name="sort_direction" value="{{ request('sort_direction', 'desc') }}">
                        </div>

                        {{-- Date Range Filters --}}
                        <div class="md:col-span-2 lg:col-span-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Installation Date Range
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                           placeholder="From Date"
                                           onchange="this.form.submit()">
                                </div>
                                <div>
                                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                           placeholder="To Date"
                                           onchange="this.form.submit()">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Active Filters Display & Clear Options --}}
                    @php
                        $activeFilters = collect([
                            'search' => request('search'),
                            'plan_filter' => request('plan_filter'),
                            'status_filter' => request('status_filter'),
                            'plan_type_filter' => request('plan_type_filter'),
                            'date_from' => request('date_from'),
                            'date_to' => request('date_to'),
                        ])->filter()->count();
                    @endphp

                    @if($activeFilters > 0)
                        <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-gray-200 dark:border-gray-700">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Active filters:</span>
                            
                            @if(request('search'))
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    Search: "{{ request('search') }}"
                                </span>
                            @endif
                            
                            @if(request('plan_filter'))
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Plan: {{ request('plan_filter') === 'no_plan' ? 'No Plan' : $plans->find(request('plan_filter'))?->name }}
                                </span>
                            @endif
                            
                            @if(request('status_filter'))
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                    Status: {{ $planStatuses[request('status_filter')] }}
                                </span>
                            @endif
                            
                            @if(request('plan_type_filter'))
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                    Type: {{ ucfirst(request('plan_type_filter')) }}
                                </span>
                            @endif
                            
                            @if(request('date_from') || request('date_to'))
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                    Date: {{ request('date_from') ?: 'Start' }} - {{ request('date_to') ?: 'End' }}
                                </span>
                            @endif
                            
                            <a href="{{ route('admin.customers.index') }}" 
                               class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                Clear All
                            </a>
                        </div>
                    @endif
                </div>
            </form>
        </div>

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        {{-- Search Results Info --}}
        @if(request()->hasAny(['search', 'plan_filter', 'status_filter', 'plan_type_filter', 'date_from', 'date_to']))
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-300 px-4 py-3 rounded-lg">
                <div class="flex items-center justify-between">
                    <span>
                        @php
                            $filterCount = collect([
                                request('search'),
                                request('plan_filter'),
                                request('status_filter'),
                                request('plan_type_filter'),
                                request('date_from'),
                                request('date_to'),
                            ])->filter()->count();
                        @endphp
                        
                        Showing {{ $customers->total() }} {{ Str::plural('customer', $customers->total()) }}
                        @if($filterCount > 0)
                            with {{ $filterCount }} {{ Str::plural('filter', $filterCount) }} applied
                        @endif
                    </span>
                    <a href="{{ route('admin.customers.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-500 underline">
                        Clear all filters
                    </a>
                </div>
            </div>
        @endif

        {{-- Customers Display - Mobile & Desktop Responsive --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
            @if($customers->count() > 0)
                {{-- Mobile Card View (hidden on desktop) --}}
                <div class="block lg:hidden">
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($customers as $customer)
                            <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">
                                                {{ $customer->full_name }}
                                            </h3>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                {{ $customer->jobOrders->count() }} jobs
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Customer #{{ $customer->id }}</p>
                                        
                                        @if($customer->email)
                                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-1">{{ $customer->email }}</p>
                                        @endif
                                        @if($customer->phone_number)
                                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">{{ $customer->phone_number }}</p>
                                        @endif
                                        @if(!$customer->email && !$customer->phone_number)
                                            <p class="text-sm text-gray-500 dark:text-gray-400 italic mb-2">No contact info</p>
                                        @endif

                                        {{-- Plan Info --}}
                                        @if($customer->plan)
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $customer->plan->name }}
                                                </span>
                                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $customer->plan->formatted_monthly_rate }}
                                                </span>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                                    @if($customer->plan_status === 'active') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @elseif($customer->plan_status === 'inactive') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 @endif">
                                                    {{ $customer->getPlanStatusLabel() }}
                                                </span>
                                            </div>
                                            @if($customer->plan_installed_at)
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                                                    Installed: {{ $customer->plan_installed_at->format('M j, Y') }}
                                                </p>
                                            @endif
                                        @else
                                            <p class="text-sm text-gray-500 dark:text-gray-400 italic mb-2">No plan assigned</p>
                                        @endif
                                        <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2">{{ $customer->service_address }}</p>
                                    </div>
                                    
                                    {{-- Mobile Actions Dropdown --}}
                                    <div class="relative ml-2" x-data="{ open: false }">
                                        <button @click="open = !open" class="flex items-center justify-center w-8 h-8 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                            </svg>
                                        </button>
                                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-1 w-32 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-10">
                                            <a href="{{ route('admin.customers.show', $customer) }}" class="block px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-t-lg">View</a>
                                            <a href="{{ route('admin.customers.edit', $customer) }}" class="block px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Edit</a>
                                            @if($customer->jobOrders->count() === 0)
                                                <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}" class="block" onsubmit="return confirm('Are you sure you want to delete this customer?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-full text-left px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-b-lg">Delete</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Desktop Table View (hidden on mobile) --}}
                <div class="hidden lg:block">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Customer
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Contact
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Plan
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Service Address
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Job Orders
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($customers as $customer)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $customer->full_name }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    Customer #{{ $customer->id }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                @if($customer->email)
                                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $customer->email }}
                                                    </div>
                                                @endif
                                                @if($customer->phone_number)
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $customer->phone_number }}
                                                    </div>
                                                @endif
                                                @if(!$customer->email && !$customer->phone_number)
                                                    <div class="text-sm text-gray-500 dark:text-gray-400 italic">
                                                        No contact info
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                @if($customer->plan)
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $customer->plan->name }}
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $customer->plan->formatted_monthly_rate }}
                                                        </span>
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                                            @if($customer->plan_status === 'active') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                            @elseif($customer->plan_status === 'inactive') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                            @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 @endif">
                                                            {{ $customer->getPlanStatusLabel() }}
                                                        </span>
                                                    </div>
                                                    @if($customer->plan_installed_at)
                                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                            Installed: {{ $customer->plan_installed_at->format('M j, Y') }}
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="text-sm text-gray-500 dark:text-gray-400 italic">
                                                        No plan
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 dark:text-gray-100 max-w-xs truncate">
                                                {{ $customer->service_address }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    {{ $customer->jobOrders->count() }} jobs
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('admin.customers.show', $customer) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                    View
                                                </a>
                                                <a href="{{ route('admin.customers.edit', $customer) }}" 
                                                   class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                                    Edit
                                                </a>
                                                @if($customer->jobOrders->count() === 0)
                                                    <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}" 
                                                          class="inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this customer?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination --}}
                @if($customers->hasPages())
                    <div class="px-4 sm:px-6 py-3 border-t border-gray-200 dark:border-gray-700">
                        {{ $customers->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                {{-- Empty State --}}
                <div class="p-6 sm:p-12 text-center">
                    <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm sm:text-base font-medium text-gray-900 dark:text-gray-100">No customers</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating your first customer.</p>
                    <div class="mt-4 sm:mt-6">
                        <a href="{{ route('admin.customers.create') }}" 
                           class="inline-flex items-center px-4 py-2 sm:px-6 sm:py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Customer
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Enhanced Search and Filter JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('customer-search');
            const filterForm = document.getElementById('filter-form');
            let searchTimeout;

            // Handle live search with debouncing
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    const query = this.value.trim();

                    searchTimeout = setTimeout(() => {
                        if (query.length >= 2) {
                            filterForm.submit();
                        } else if (query.length === 0) {
                            // Clear search but preserve other filters
                            const url = new URL(window.location.href);
                            url.searchParams.delete('search');
                            window.location.href = url.toString();
                        }
                    }, 500);
                });

                // Handle form submission (Enter key)
                filterForm.addEventListener('submit', function(e) {
                    const query = searchInput.value.trim();
                    if (query.length === 0) {
                        e.preventDefault();
                        // Clear search but preserve other filters
                        const url = new URL(window.location.href);
                        url.searchParams.delete('search');
                        window.location.href = url.toString();
                    }
                });
            }

            // Handle sort direction toggle
            const sortSelect = document.getElementById('sort_by');
            const sortDirectionInput = document.querySelector('input[name="sort_direction"]');
            
            if (sortSelect && sortDirectionInput) {
                sortSelect.addEventListener('change', function() {
                    // Reset to desc for new sort fields, except for names which should be asc
                    if (['first_name', 'last_name', 'email'].includes(this.value)) {
                        sortDirectionInput.value = 'asc';
                    } else {
                        sortDirectionInput.value = 'desc';
                    }
                });
            }

            // Add keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Focus search on '/' key
                if (e.key === '/' && !e.ctrlKey && !e.metaKey && !e.altKey) {
                    e.preventDefault();
                    searchInput?.focus();
                }
                
                // Clear all filters on Escape key when search is focused
                if (e.key === 'Escape' && document.activeElement === searchInput) {
                    window.location.href = '{{ route("admin.customers.index") }}';
                }
            });
        });
    </script>
</x-layouts.app>
