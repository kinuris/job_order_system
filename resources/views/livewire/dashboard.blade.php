<div class="space-y-4 sm:space-y-6 px-2 sm:px-0">
    @if($isAdmin)
        {{-- Admin Dashboard --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Admin Dashboard</h1>
            <p class="text-gray-600 dark:text-gray-400">Job Order Management System Overview</p>
        </div>

        {{-- Stats Grid --}}
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Total Customers</p>
                        <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $stats['total_customers'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-green-600 dark:text-green-400">Total Job Orders</p>
                        <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ $stats['total_job_orders'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-yellow-600 dark:text-yellow-400">Pending Jobs</p>
                        <p class="text-2xl font-bold text-yellow-900 dark:text-yellow-100">{{ $stats['pending_job_orders'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-purple-600 dark:text-purple-400">In Progress</p>
                        <p class="text-2xl font-bold text-purple-900 dark:text-purple-100">{{ $stats['in_progress_job_orders'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Quick Actions</h2>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.customers.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Customer
                </a>
                <a href="{{ route('admin.job-orders.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Job Order
                </a>
                <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    View All Customers
                </a>
                <a href="{{ route('admin.job-orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    View All Job Orders
                </a>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="grid gap-6 md:grid-cols-2">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Recent Job Orders</h3>
                @if($recent_job_orders && $recent_job_orders->count() > 0)
                    <div class="space-y-3">
                        @foreach($recent_job_orders as $job)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg
                                {{ $job->isLate() ? 'border-l-4 border-red-500 bg-red-50 dark:bg-red-900/10' : '' }}">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <p class="font-medium text-gray-900 dark:text-gray-100">#{{ $job->id }} - {{ $job->customer->first_name }} {{ $job->customer->last_name }}</p>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            @if($job->priority === 'urgent') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                                            @elseif($job->priority === 'high') bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300
                                            @elseif($job->priority === 'medium') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                            @else bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 @endif">
                                            {{ ucfirst($job->priority) }}
                                        </span>
                                        @if($job->isLate())
                                            <span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                                {{ $job->getDaysLate() }} {{ $job->getDaysLate() == 1 ? 'day' : 'days' }} overdue
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ ucfirst(str_replace('_', ' ', $job->status)) }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button wire:click="openChatModal({{ $job->id }})" 
                                            class="px-2 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a9.863 9.863 0 01-4.906-1.287A3 3 0 014 19h1a8 8 0 016-7.265M17 12a8 8 0 00-8-8 8 8 0 00-8 8"></path>
                                        </svg>
                                        Chat
                                        @if($this->getUnreadMessageCount($job->id) > 0)
                                            <span class="bg-red-500 text-white rounded-full px-1 text-xs">{{ $this->getUnreadMessageCount($job->id) }}</span>
                                        @endif
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">No job orders created today.</p>
                @endif
            </div>

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Recent Customers</h3>
                @if($recent_customers && $recent_customers->count() > 0)
                    <div class="space-y-3">
                        @foreach($recent_customers as $customer)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $customer->first_name }} {{ $customer->last_name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $customer->email }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $customer->jobOrders->count() }} jobs</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">No customers yet.</p>
                @endif
            </div>
        </div>

    @elseif($isTechnician)
        {{-- Technician Dashboard --}}
        <div class="px-1">
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">Technician Dashboard</h1>
            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400">Welcome back, {{ $user->name }}!</p>
        </div>

        {{-- Technician Stats - Mobile Optimized --}}
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg sm:rounded-xl p-3 sm:p-6">
                <div class="text-center">
                    <div class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 mx-auto mb-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-lg sm:text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $stats['pending_jobs'] }}</p>
                    <p class="text-xs sm:text-sm text-blue-600 dark:text-blue-400 leading-tight">Pending</p>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg sm:rounded-xl p-3 sm:p-6">
                <div class="text-center">
                    <div class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 mx-auto mb-2 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <p class="text-lg sm:text-2xl font-bold text-yellow-900 dark:text-yellow-100">{{ $stats['scheduled_jobs'] }}</p>
                    <p class="text-xs sm:text-sm text-yellow-600 dark:text-yellow-400 leading-tight">Scheduled</p>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg sm:rounded-xl p-3 sm:p-6">
                <div class="text-center">
                    <div class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 mx-auto mb-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <p class="text-lg sm:text-2xl font-bold text-green-900 dark:text-green-100">{{ $stats['in_progress_jobs'] }}</p>
                    <p class="text-xs sm:text-sm text-green-600 dark:text-green-400 leading-tight">In Progress</p>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg sm:rounded-xl p-3 sm:p-6">
                <div class="text-center">
                    <div class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 mx-auto mb-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-lg sm:text-2xl font-bold text-purple-900 dark:text-purple-100">{{ $stats['completed_today'] }}</p>
                    <p class="text-xs sm:text-sm text-purple-600 dark:text-purple-400 leading-tight">Completed</p>
                </div>
            </div>
        </div>

        {{-- My Job Orders - Mobile Optimized --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg sm:rounded-xl p-3 sm:p-6">
            {{-- Header --}}
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Assigned Jobs</h3>
                
                {{-- Mobile-First Controls --}}
                <div class="space-y-3">
                    {{-- Search Bar --}}
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input wire:model.live.debounce.300ms="search" 
                               type="text" 
                               placeholder="Search jobs..." 
                               class="w-full pl-10 pr-10 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @if($search)
                            <button wire:click="clearSearch" 
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                    @if($search)
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Searching for: "<span class="font-medium">{{ $search }}</span>"
                        </p>
                    @endif
                    
                    {{-- Controls Row --}}
                    <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                        {{-- Sort Dropdown --}}
                        <div class="flex items-center gap-2">
                            <label class="text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap">Sort:</label>
                            <select wire:model.live="sortBy" 
                                    class="flex-1 sm:flex-none text-sm px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="scheduled">📅 By Date</option>
                                <option value="priority">⚡ By Priority</option>
                            </select>
                            @if($sortBy === 'priority')
                                <span class="hidden sm:inline text-xs px-2 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 rounded-full whitespace-nowrap">
                                    Urgent → Low
                                </span>
                            @else
                                <span class="hidden sm:inline text-xs px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 rounded-full whitespace-nowrap">
                                    Late → Today
                                </span>
                            @endif
                        </div>

                        {{-- Completed Jobs Toggle --}}
                        <button wire:click="toggleCompletedJobs" 
                                class="flex items-center justify-center gap-2 px-4 py-2 text-sm rounded-lg border transition-colors font-medium min-h-[40px]
                                {{ $showCompletedJobs 
                                    ? 'bg-green-50 text-green-700 border-green-200 hover:bg-green-100 dark:bg-green-900/20 dark:text-green-300 dark:border-green-700' 
                                    : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100 dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($showCompletedJobs)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                @endif
                            </svg>
                            <span class="sm:hidden">{{ $showCompletedJobs ? 'Hide' : 'Show' }} Done</span>
                            <span class="hidden sm:inline">{{ $showCompletedJobs ? 'Hide' : 'Show' }} Completed Jobs</span>
                        </button>
                    </div>
                </div>
            </div>
            @if($my_job_orders && $my_job_orders->count() > 0)
                {{-- Pagination --}}
                <div class="mb-4">
                    {{ $my_job_orders->links() }}
                </div>
                
                {{-- Mobile-Optimized Job Cards --}}
                <div class="space-y-3 sm:space-y-4">
                    @foreach($my_job_orders as $job)
                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-3 sm:p-4 
                            @if($job->status === 'completed')
                                bg-green-50 dark:bg-green-900/10 border-green-200 dark:border-green-700 opacity-80
                            @elseif($job->isLate())
                                border-red-300 dark:border-red-600 bg-red-50 dark:bg-red-900/10
                            @else
                                bg-gray-50 dark:bg-gray-700/50
                            @endif">
                            
                            {{-- Mobile-First Job Header --}}
                            <div class="space-y-2">
                                {{-- Top Row: Job ID and Status Badges --}}
                                <div class="flex items-start justify-between">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 text-base">Job #{{ $job->id }}</h4>
                                        @if($job->status === 'completed')
                                            <span class="px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                ✅ DONE
                                            </span>
                                        @elseif($job->isLate())
                                            <span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 animate-pulse">
                                                {{ $job->getDaysLate() }}D LATE
                                            </span>
                                        @endif
                                    </div>
                                    
                                    {{-- Chat Button - Always Visible on Mobile --}}
                                    <button wire:click="openChatModal({{ $job->id }})" 
                                            class="flex items-center gap-1 px-3 py-2 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition-colors min-h-[36px]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a9.863 9.863 0 01-4.906-1.287A3 3 0 014 19h1a8 8 0 016-7.265M17 12a8 8 0 00-8-8 8 8 0 00-8 8"></path>
                                        </svg>
                                        <span class="hidden sm:inline">Chat</span>
                                        @if($this->getUnreadMessageCount($job->id) > 0)
                                            <span class="bg-red-500 text-white rounded-full px-1.5 py-0.5 text-xs font-bold min-w-[18px] text-center">{{ $this->getUnreadMessageCount($job->id) }}</span>
                                        @endif
                                    </button>
                                </div>
                                
                                {{-- Customer and Priority Row --}}
                                <div class="flex flex-wrap items-center gap-2">
                                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $job->customer->first_name }} {{ $job->customer->last_name }}</p>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($job->priority === 'urgent') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                                        @elseif($job->priority === 'high') bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300
                                        @elseif($job->priority === 'medium') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                        @else bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 @endif">
                                        {{ ucfirst($job->priority) }}
                                    </span>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($job->status === 'pending_dispatch') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                        @elseif($job->status === 'scheduled') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                        @elseif($job->status === 'en_route') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300
                                        @elseif($job->status === 'in_progress') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                        @elseif($job->status === 'on_hold') bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300
                                        @elseif($job->status === 'completed') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                                    </span>
                                </div>
                                
                                {{-- Address --}}
                                <div class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <p class="text-base text-gray-800 dark:text-gray-200 leading-relaxed">{{ $job->customer->service_address }}</p>
                                </div>
                                
                                {{-- Date Information --}}
                                @if($job->status === 'completed' && $job->completed_at)
                                    <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>Completed: {{ $job->completed_at->format('M d, Y \a\t g:i A') }}
                                        @if($job->completed_at->isToday())
                                            <span class="text-green-600 dark:text-green-400 font-medium">(TODAY)</span>
                                        @endif
                                        </span>
                                    </div>
                                @elseif($job->scheduled_at)
                                    <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                        <svg class="w-4 h-4 {{ $job->isLate() ? 'text-red-500' : ($job->scheduled_at->isToday() ? 'text-blue-500' : 'text-gray-400') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>Scheduled: {{ $job->scheduled_at->format('M d, Y') }}
                                        @if($job->isLate())
                                            <span class="text-red-600 dark:text-red-400 font-medium">(OVERDUE)</span>
                                        @elseif($job->scheduled_at->isToday())
                                            <span class="text-blue-600 dark:text-blue-400 font-medium">(TODAY)</span>
                                        @endif
                                        </span>
                                    </div>
                                @endif
                            </div>

                            {{-- Job Details --}}
                            <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                                <div class="space-y-2">
                                    <div>
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Description</span>
                                        <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">{{ $job->description }}</p>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div>
                                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Type</span>
                                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ ucfirst($job->type) }}</p>
                                        </div>
                                    </div>
                                    @if($job->resolution_notes)
                                        <div>
                                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Notes</span>
                                            <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">{{ $job->resolution_notes }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Mobile-Optimized Action Buttons --}}
                            @if($isTechnician)
                                <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                                        @if(!in_array($job->status, ['completed', 'cancelled']))
                                            <button wire:click="openStatusModal({{ $job->id }})" 
                                                    class="flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Update Status
                                            </button>
                                        @endif
                                        
                                        {{-- Edit Notes button is always available for technicians, even for completed jobs --}}
                                        @if($job->status !== 'cancelled')
                                            <button wire:click="openNotesModal({{ $job->id }})" 
                                                    class="flex items-center justify-center gap-2 px-4 py-3 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors font-medium {{ in_array($job->status, ['completed', 'cancelled']) ? 'sm:col-span-3' : '' }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit Notes
                                            </button>
                                        @endif
                                        
                                        @if(!in_array($job->status, ['completed', 'cancelled']))
                                            <button wire:click="openRescheduleModal({{ $job->id }})" 
                                                    class="flex items-center justify-center gap-2 px-4 py-3 bg-orange-600 text-white text-sm rounded-lg hover:bg-orange-700 transition-colors font-medium sm:col-span-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                Reschedule
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400">No assigned jobs.</p>
            @endif
        </div>

    @else
        {{-- Default Dashboard --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Dashboard</h1>
            <p class="text-gray-600 dark:text-gray-400">Welcome, {{ $user->name }}!</p>
        </div>

        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
            <p class="text-gray-600 dark:text-gray-400">Your role: {{ ucfirst($user->role) }}</p>
        </div>
    @endif

    {{-- Mobile-Optimized Chat Modal --}}
    @if($showChatModal)
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 transition-all duration-300">
        <div class="flex items-end sm:items-center justify-center min-h-screen p-2 sm:p-4">
            <div class="bg-white dark:bg-gray-800 rounded-t-2xl sm:rounded-xl shadow-2xl w-full max-w-3xl h-[85vh] sm:h-[32rem] flex flex-col border border-gray-200 dark:border-gray-600 transform transition-all duration-300" 
                 x-data="{ scrollToBottom() { setTimeout(() => { const container = this.$refs.messagesContainer; if (container) container.scrollTop = container.scrollHeight; }, 100); } }"
                 x-init="scrollToBottom()"
                 @chat-message-sent.window="scrollToBottom()">
                
                {{-- Mobile-Optimized Chat Header --}}
                <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200 dark:border-gray-600 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-800 rounded-t-2xl sm:rounded-t-xl">
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a9.863 9.863 0 01-4.906-1.287A3 3 0 014 19h1a8 8 0 016-7.265M17 12a8 8 0 00-8-8 8 8 0 00-8 8"></path>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-gray-100 truncate">{{ $chatTitle }}</h3>
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 hidden sm:block">Real-time communication</p>
                        </div>
                    </div>
                    <button wire:click="closeChatModal" class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Mobile-Optimized Chat Messages --}}
                <div x-ref="messagesContainer" class="flex-1 overflow-y-auto p-3 sm:p-6 space-y-3 sm:space-y-4 bg-gray-50 dark:bg-gray-900/50">
                    @if(empty($chatMessages))
                        <div class="text-center py-8 sm:py-12">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a9.863 9.863 0 01-4.906-1.287A3 3 0 014 19h1a8 8 0 016-7.265M17 12a8 8 0 00-8-8 8 8 0 00-8 8"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 text-base sm:text-lg">No messages yet</p>
                            <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Start the conversation!</p>
                        </div>
                    @else
                        @foreach($chatMessages as $message)
                            <div class="flex {{ $message['is_own_message'] ? 'justify-end' : 'justify-start' }} animate-fade-in">
                                <div class="max-w-[85%] sm:max-w-sm lg:max-w-md">
                                    @if(!$message['is_own_message'])
                                        <div class="flex items-center mb-1 ml-1">
                                            <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-full {{ $message['user_role'] === 'admin' ? 'bg-blue-100 dark:bg-blue-900/30' : 'bg-green-100 dark:bg-green-900/30' }} flex items-center justify-center mr-2">
                                                <span class="text-xs font-semibold {{ $message['user_role'] === 'admin' ? 'text-blue-600 dark:text-blue-400' : 'text-green-600 dark:text-green-400' }}">
                                                    {{ substr($message['user_name'], 0, 1) }}
                                                </span>
                                            </div>
                                            <span class="text-xs font-medium {{ $message['user_role'] === 'admin' ? 'text-blue-600 dark:text-blue-400' : 'text-green-600 dark:text-green-400' }} truncate">
                                                {{ $message['user_name'] }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-2 px-1.5 py-0.5 bg-gray-100 dark:bg-gray-700 rounded-full hidden sm:inline">
                                                {{ ucfirst($message['user_role']) }}
                                            </span>
                                        </div>
                                    @endif
                                    <div class="px-3 py-2.5 sm:px-4 sm:py-3 rounded-2xl shadow-sm {{ $message['is_own_message'] 
                                        ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-br-md' 
                                        : 'bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-900 dark:text-gray-100 rounded-bl-md' }}">
                                        <div class="text-sm leading-relaxed whitespace-pre-wrap">{{ $message['message'] }}</div>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 {{ $message['is_own_message'] ? 'text-right mr-1' : 'ml-1' }}">
                                        {{ $message['created_at'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- Mobile-Optimized Chat Input --}}
                <div class="p-3 sm:p-6 border-t border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 rounded-b-2xl sm:rounded-b-xl">
                    <form wire:submit="sendMessage" class="flex gap-2 sm:gap-3">
                        <div class="flex-1 relative">
                            <input type="text" 
                                   wire:model="newMessage"
                                   placeholder="Type your message..." 
                                   class="w-full px-3 py-3 sm:px-4 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 pr-10 text-sm sm:text-base"
                                   required>
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                            </div>
                        </div>
                        <button type="submit" 
                                class="px-4 py-3 sm:px-6 sm:py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 flex items-center gap-2 font-medium flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            <span class="hidden sm:inline">Send</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Status Update Modal --}}
    @if($showStatusModal)
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 transition-all duration-300">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md border border-gray-200 dark:border-gray-600">
                <div class="p-6 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Update Job Status</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Job #{{ $editingJobId }}</p>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                            <select wire:model="editingStatus" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="en_route">En Route</option>
                                <option value="in_progress">In Progress</option>
                                <option value="on_hold">On Hold</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-600">
                    <button wire:click="closeStatusModal" 
                            class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-600 rounded-md hover:bg-gray-200 dark:hover:bg-gray-500 transition-colors">
                        Cancel
                    </button>
                    <button wire:click="updateJobStatus" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Update Status
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Notes Update Modal --}}
    @if($showNotesModal)
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 transition-all duration-300">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-lg border border-gray-200 dark:border-gray-600">
                <div class="p-6 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Edit Job Notes</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Job #{{ $editingJobId }}</p>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Resolution Notes</label>
                            <textarea wire:model="editingNotes" 
                                      rows="4" 
                                      placeholder="Enter notes about the job progress, issues, or resolution..."
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"></textarea>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Maximum 1000 characters</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-600">
                    <button wire:click="closeNotesModal" 
                            class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-600 rounded-md hover:bg-gray-200 dark:hover:bg-gray-500 transition-colors">
                        Cancel
                    </button>
                    <button wire:click="updateJobNotes" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                        Update Notes
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Reschedule Modal --}}
    @if($showRescheduleModal)
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 transition-all duration-300">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md border border-gray-200 dark:border-gray-600">
                <div class="p-6 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Reschedule Job</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Job #{{ $editingJobId }}</p>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Date</label>
                            <input type="date" 
                                   wire:model="rescheduleDate" 
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Time</label>
                            <input type="time" 
                                   wire:model="rescheduleTime" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-600">
                    <button wire:click="closeRescheduleModal" 
                            class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-600 rounded-md hover:bg-gray-200 dark:hover:bg-gray-500 transition-colors">
                        Cancel
                    </button>
                    <button wire:click="rescheduleJob" 
                            class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 transition-colors">
                        Reschedule
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-bounce">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-bounce">
            {{ session('error') }}
        </div>
    @endif

    {{-- Mobile-Optimized Styles --}}
    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
        
        /* Mobile-first scrollbar styling */
        ::-webkit-scrollbar {
            width: 4px;
        }
        
        @media (min-width: 640px) {
            ::-webkit-scrollbar {
                width: 6px;
            }
        }
        
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 6px;
        }
        
        .dark ::-webkit-scrollbar-thumb {
            background: #4b5563;
        }
        
        /* Prevent horizontal scroll on mobile */
        body {
            overflow-x: hidden;
        }
        
        /* Touch-friendly tap targets */
        @media (max-width: 640px) {
            button, a, input, select {
                min-height: 44px;
                min-width: 44px;
            }
            
            /* Prevent zoom on input focus on iOS */
            input, select, textarea {
                font-size: 16px;
            }
        }
        
        /* Better text readability on mobile */
        @media (max-width: 640px) {
            .text-xs {
                font-size: 0.75rem;
                line-height: 1.2;
            }
            
            .text-sm {
                font-size: 0.875rem;
                line-height: 1.4;
            }
        }
        
        /* Smooth scrolling for job list */
        .space-y-3 > *, .space-y-4 > * {
            scroll-margin-top: 1rem;
        }
        
        /* Enhanced mobile modal */
        @media (max-width: 640px) {
            .fixed.inset-0 {
                padding: 0;
            }
            
            .rounded-t-2xl {
                border-radius: 1rem 1rem 0 0;
            }
            
            /* Make modals feel more native on mobile */
            .bg-black\/60 {
                backdrop-filter: blur(8px);
            }
        }
    </style>
</div>

{{-- Auto-refresh chat every 5 seconds when modal is open --}}
<script>
    document.addEventListener('livewire:initialized', () => {
        let chatRefreshInterval;
        
        // Listen for modal open/close events
        window.addEventListener('chat-modal-opened', () => {
            chatRefreshInterval = setInterval(() => {
                Livewire.dispatch('refresh-chat');
            }, 5000);
        });
        
        window.addEventListener('chat-modal-closed', () => {
            if (chatRefreshInterval) {
                clearInterval(chatRefreshInterval);
            }
        });
        
        // Listen for sort updates
        Livewire.on('sortUpdated', (data) => {
            console.log('Sort updated to:', data.sortBy);
        });
        
        // Listen for completed jobs toggle
        Livewire.on('completedJobsToggled', (data) => {
            console.log('Completed jobs toggle:', data.showing ? 'shown' : 'hidden');
        });
        
        // Auto-hide flash messages after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('[class*="fixed bottom-4 right-4"]').forEach(element => {
                if (element.style.opacity !== '0') {
                    element.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    element.style.opacity = '0';
                    element.style.transform = 'translateX(100%)';
                    setTimeout(() => {
                        if (element.parentNode) {
                            element.parentNode.removeChild(element);
                        }
                    }, 300);
                }
            });
        }, 5000);
        
        // Listen for new flash messages and apply auto-hide to them
        document.addEventListener('livewire:navigated', () => {
            setTimeout(() => {
                document.querySelectorAll('[class*="fixed bottom-4 right-4"]').forEach(element => {
                    if (element.style.opacity !== '0') {
                        element.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                        element.style.opacity = '0';
                        element.style.transform = 'translateX(100%)';
                        setTimeout(() => {
                            if (element.parentNode) {
                                element.parentNode.removeChild(element);
                            }
                        }, 300);
                    }
                });
            }, 5000);
        });
    });
</script>
