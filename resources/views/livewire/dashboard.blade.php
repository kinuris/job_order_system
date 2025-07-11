<div class="space-y-6">
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
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Technician Dashboard</h1>
            <p class="text-gray-600 dark:text-gray-400">Welcome back, {{ $user->name }}!</p>
        </div>

        {{-- Technician Stats --}}
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <div class="text-center">
                    <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $stats['pending_jobs'] }}</p>
                    <p class="text-sm text-blue-600 dark:text-blue-400">Pending Jobs</p>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <div class="text-center">
                    <p class="text-2xl font-bold text-yellow-900 dark:text-yellow-100">{{ $stats['scheduled_jobs'] }}</p>
                    <p class="text-sm text-yellow-600 dark:text-yellow-400">Scheduled</p>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <div class="text-center">
                    <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ $stats['in_progress_jobs'] }}</p>
                    <p class="text-sm text-green-600 dark:text-green-400">In Progress</p>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <div class="text-center">
                    <p class="text-2xl font-bold text-purple-900 dark:text-purple-100">{{ $stats['completed_today'] }}</p>
                    <p class="text-sm text-purple-600 dark:text-purple-400">Completed Today</p>
                </div>
            </div>
        </div>

        {{-- My Job Orders --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Assigned Jobs</h3>
                
                {{-- Sort Options and Toggle --}}
                <div class="flex items-center gap-4">
                    {{-- Sort Dropdown --}}
                    <div class="flex items-center gap-2">
                        <label class="text-sm text-gray-600 dark:text-gray-400">Sort by:</label>
                        <div class="relative">
                            <select wire:model.live="sortBy" 
                                    class="text-sm px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="scheduled">📅 Schedule Date</option>
                                <option value="priority">⚡ Priority</option>
                            </select>
                        </div>
                        @if($sortBy === 'priority')
                            <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 rounded-full">
                                Urgent → Low
                            </span>
                        @else
                            <span class="text-xs px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 rounded-full">
                                Late → Today
                            </span>
                        @endif
                    </div>

                    {{-- Completed Jobs Toggle --}}
                    <div class="flex items-center gap-2">
                        <button wire:click="toggleCompletedJobs" 
                                class="flex items-center gap-2 px-3 py-1 text-sm rounded-md border transition-colors
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
                            {{ $showCompletedJobs ? 'Hide' : 'Show' }} Completed Today
                        </button>
                    </div>
                </div>
            </div>
            @if($my_job_orders && $my_job_orders->count() > 0)
                <div class="space-y-4">
                    @foreach($my_job_orders as $job)
                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 
                            @if($job->status === 'completed')
                                bg-green-50 dark:bg-green-900/10 border-green-200 dark:border-green-700 opacity-80
                            @elseif($job->isLate())
                                border-red-300 dark:border-red-600 bg-red-50 dark:bg-red-900/10
                            @else
                                bg-gray-50 dark:bg-gray-700/50
                            @endif">
                            {{-- Job Header --}}
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <h4 class="font-medium text-gray-900 dark:text-gray-100">Job #{{ $job->id }}</h4>
                                        @if($job->status === 'completed')
                                            <span class="px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                ✅ COMPLETED TODAY
                                            </span>
                                        @elseif($job->isLate())
                                            <span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 animate-pulse">
                                                {{ $job->getDaysLate() }} {{ $job->getDaysLate() == 1 ? 'DAY' : 'DAYS' }} LATE
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $job->customer->first_name }} {{ $job->customer->last_name }}</p>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            @if($job->priority === 'urgent') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                                            @elseif($job->priority === 'high') bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300
                                            @elseif($job->priority === 'medium') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                            @else bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 @endif">
                                            {{ ucfirst($job->priority) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $job->customer->service_address }}</p>
                                </div>
                                <div class="text-right flex flex-col items-end">
                                    <div class="mb-2">
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
                                    @if($job->status === 'completed' && $job->completed_at)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            Completed: {{ $job->completed_at->format('M d, Y \a\t g:i A') }}
                                            @if($job->completed_at->isToday())
                                                <span class="text-green-600 dark:text-green-400 font-medium">(TODAY)</span>
                                            @endif
                                        </p>
                                    @elseif($job->scheduled_at)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            Scheduled: {{ $job->scheduled_at->format('M d, Y') }}
                                            @if($job->isLate())
                                                <span class="text-red-600 dark:text-red-400 font-medium">(OVERDUE)</span>
                                            @elseif($job->scheduled_at->isToday())
                                                <span class="text-blue-600 dark:text-blue-400 font-medium">(TODAY)</span>
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            </div>

                            {{-- Job Description --}}
                            <div class="mb-3">
                                <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Description:</strong> {{ $job->description }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Type:</strong> {{ ucfirst($job->type) }}</p>
                                @if($job->resolution_notes)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2"><strong>Notes:</strong> {{ $job->resolution_notes }}</p>
                                @endif
                            </div>

                            {{-- Technician Action Buttons --}}
                            @if($isTechnician && !in_array($job->status, ['completed', 'cancelled']))
                                <div class="flex flex-wrap gap-2 pt-3 border-t border-gray-200 dark:border-gray-600">
                                    <button wire:click="openStatusModal({{ $job->id }})" 
                                            class="flex items-center gap-1 px-3 py-1 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700 transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Update Status
                                    </button>
                                    <button wire:click="openNotesModal({{ $job->id }})" 
                                            class="flex items-center gap-1 px-3 py-1 bg-green-600 text-white text-xs rounded-md hover:bg-green-700 transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit Notes
                                    </button>
                                    <button wire:click="openRescheduleModal({{ $job->id }})" 
                                            class="flex items-center gap-1 px-3 py-1 bg-orange-600 text-white text-xs rounded-md hover:bg-orange-700 transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Reschedule
                                    </button>
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

    {{-- Chat Modal --}}
    @if($showChatModal)
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 transition-all duration-300">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-3xl h-[32rem] flex flex-col border border-gray-200 dark:border-gray-600 transform transition-all duration-300" 
                 x-data="{ scrollToBottom() { setTimeout(() => { const container = this.$refs.messagesContainer; if (container) container.scrollTop = container.scrollHeight; }, 100); } }"
                 x-init="scrollToBottom()"
                 @chat-message-sent.window="scrollToBottom()">
                
                {{-- Chat Header --}}
                <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-600 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-800 rounded-t-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a9.863 9.863 0 01-4.906-1.287A3 3 0 014 19h1a8 8 0 016-7.265M17 12a8 8 0 00-8-8 8 8 0 00-8 8"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $chatTitle }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Real-time communication</p>
                        </div>
                    </div>
                    <button wire:click="closeChatModal" class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Chat Messages --}}
                <div x-ref="messagesContainer" class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50 dark:bg-gray-900/50">
                    @if(empty($chatMessages))
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a9.863 9.863 0 01-4.906-1.287A3 3 0 014 19h1a8 8 0 016-7.265M17 12a8 8 0 00-8-8 8 8 0 00-8 8"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 text-lg">No messages yet</p>
                            <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Start the conversation!</p>
                        </div>
                    @else
                        @foreach($chatMessages as $message)
                            <div class="flex {{ $message['is_own_message'] ? 'justify-end' : 'justify-start' }} animate-fade-in">
                                <div class="max-w-sm lg:max-w-md">
                                    @if(!$message['is_own_message'])
                                        <div class="flex items-center mb-1 ml-1">
                                            <div class="w-6 h-6 rounded-full {{ $message['user_role'] === 'admin' ? 'bg-blue-100 dark:bg-blue-900/30' : 'bg-green-100 dark:bg-green-900/30' }} flex items-center justify-center mr-2">
                                                <span class="text-xs font-semibold {{ $message['user_role'] === 'admin' ? 'text-blue-600 dark:text-blue-400' : 'text-green-600 dark:text-green-400' }}">
                                                    {{ substr($message['user_name'], 0, 1) }}
                                                </span>
                                            </div>
                                            <span class="text-xs font-medium {{ $message['user_role'] === 'admin' ? 'text-blue-600 dark:text-blue-400' : 'text-green-600 dark:text-green-400' }}">
                                                {{ $message['user_name'] }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-2 px-2 py-0.5 bg-gray-100 dark:bg-gray-700 rounded-full">
                                                {{ ucfirst($message['user_role']) }}
                                            </span>
                                        </div>
                                    @endif
                                    <div class="px-4 py-3 rounded-2xl shadow-sm {{ $message['is_own_message'] 
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

                {{-- Chat Input --}}
                <div class="p-6 border-t border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 rounded-b-xl">
                    <form wire:submit="sendMessage" class="flex gap-3">
                        <div class="flex-1 relative">
                            <input type="text" 
                                   wire:model="newMessage"
                                   placeholder="Type your message..." 
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 pr-12"
                                   required>
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                            </div>
                        </div>
                        <button type="submit" 
                                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 flex items-center gap-2 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Send
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

    {{-- Styles --}}
    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
        
        ::-webkit-scrollbar {
            width: 6px;
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
