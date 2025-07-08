<x-layouts.app :title="__('Dashboard')">
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
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Today's Job Orders</h3>
                    @if($recent_job_orders->count() > 0)
                        <div class="space-y-3">
                            @foreach($recent_job_orders as $job)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-gray-100">#{{ $job->id }} - {{ $job->customer->full_name }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ ucfirst(str_replace('_', ' ', $job->status)) }}</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($job->priority === 'urgent') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                                        @elseif($job->priority === 'high') bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300
                                        @elseif($job->priority === 'medium') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                        @else bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 @endif">
                                        {{ ucfirst($job->priority) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">No job orders created today.</p>
                    @endif
                </div>

                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Recent Customers</h3>
                    @if($recent_customers->count() > 0)
                        <div class="space-y-3">
                            @foreach($recent_customers as $customer)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ $customer->full_name }}</p>
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
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Today's Assigned Jobs</h3>
                @if($my_job_orders->count() > 0)
                    <div class="space-y-3">
                        @foreach($my_job_orders as $job)
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">Job #{{ $job->id }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $job->customer->full_name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $job->description }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($job->status === 'pending_dispatch') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                        @elseif($job->status === 'scheduled') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                        @elseif($job->status === 'in_progress') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                                    </span>
                                    @if($job->scheduled_at)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $job->scheduled_at->format('M d, H:i') }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">No assigned jobs for today.</p>
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
    </div>
</x-layouts.app>
