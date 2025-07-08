<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        @if($isAdmin)
            {{-- Admin Dashboard --}}
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
                <p class="text-gray-600">Job Order Management System Overview</p>
            </div>

            {{-- Stats Grid --}}
            <div class="grid auto-rows-min gap-4 md:grid-cols-4">
                <div class="bg-blue-50/20 border border-blue-200 rounded-xl p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-blue-600">Total Customers</p>
                            <p class="text-2xl font-bold text-blue-900">{{ $stats['total_customers'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50/20 border border-green-200 rounded-xl p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-green-600">Total Job Orders</p>
                            <p class="text-2xl font-bold text-green-900">{{ $stats['total_job_orders'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50/20 border border-yellow-200 rounded-xl p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-yellow-600">Pending Jobs</p>
                            <p class="text-2xl font-bold text-yellow-900">{{ $stats['pending_job_orders'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-purple-50/20 border border-purple-200 rounded-xl p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-purple-600">In Progress</p>
                            <p class="text-2xl font-bold text-purple-900">{{ $stats['in_progress_job_orders'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white border border-gray-200 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.customers.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create Customer
                    </a>
                    <a href="{{ route('admin.job-orders.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create Job Order
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        View All Customers
                    </a>
                    <a href="{{ route('admin.job-orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        View All Job Orders
                    </a>
                </div>
            </div>

            {{-- Recent Activity --}}
            <div class="grid gap-6 md:grid-cols-2">
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Job Orders</h3>
                    @if($recent_job_orders->count() > 0)
                        <div class="space-y-3">
                            @foreach($recent_job_orders as $job)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900">#{{ $job->id }} - {{ $job->customer->full_name }}</p>
                                        <p class="text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $job->status)) }}</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($job->priority === 'urgent') bg-red-100 text-red-800
                                        @elseif($job->priority === 'high') bg-orange-100 text-orange-800
                                        @elseif($job->priority === 'medium') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800 @endif">
                                        {{ ucfirst($job->priority) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No job orders yet.</p>
                    @endif
                </div>

                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Customers</h3>
                    @if($recent_customers->count() > 0)
                        <div class="space-y-3">
                            @foreach($recent_customers as $customer)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $customer->full_name }}</p>
                                        <p class="text-sm text-gray-600">{{ $customer->email }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-600">{{ $customer->jobOrders->count() }} jobs</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No customers yet.</p>
                    @endif
                </div>
            </div>

        @elseif($isTechnician)
            {{-- Technician Dashboard --}}
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Technician Dashboard</h1>
                <p class="text-gray-600">Welcome back, {{ $user->name }}!</p>
            </div>

            {{-- Technician Stats --}}
            <div class="grid auto-rows-min gap-4 md:grid-cols-4">
                <div class="bg-blue-50/20 border border-blue-200 rounded-xl p-6">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-blue-900">{{ $stats['pending_jobs'] }}</p>
                        <p class="text-sm text-blue-600">Pending Jobs</p>
                    </div>
                </div>
                <div class="bg-yellow-50/20 border border-yellow-200 rounded-xl p-6">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-yellow-900">{{ $stats['scheduled_jobs'] }}</p>
                        <p class="text-sm text-yellow-600">Scheduled</p>
                    </div>
                </div>
                <div class="bg-green-50/20 border border-green-200 rounded-xl p-6">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-green-900">{{ $stats['in_progress_jobs'] }}</p>
                        <p class="text-sm text-green-600">In Progress</p>
                    </div>
                </div>
                <div class="bg-purple-50/20 border border-purple-200 rounded-xl p-6">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-purple-900">{{ $stats['completed_today'] }}</p>
                        <p class="text-sm text-purple-600">Completed Today</p>
                    </div>
                </div>
            </div>

            {{-- My Job Orders --}}
            <div class="bg-white border border-gray-200 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">My Assigned Jobs</h3>
                @if($my_job_orders->count() > 0)
                    <div class="space-y-3">
                        @foreach($my_job_orders as $job)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">Job #{{ $job->id }}</p>
                                    <p class="text-sm text-gray-600">{{ $job->customer->full_name }}</p>
                                    <p class="text-sm text-gray-600">{{ $job->description }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($job->status === 'pending_dispatch') bg-yellow-100 text-yellow-800
                                        @elseif($job->status === 'scheduled') bg-blue-100 text-blue-800
                                        @elseif($job->status === 'in_progress') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                                    </span>
                                    @if($job->scheduled_at)
                                        <p class="text-xs text-gray-500 mt-1">{{ $job->scheduled_at->format('M d, H:i') }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">No assigned jobs at the moment.</p>
                @endif
            </div>

        @else
            {{-- Default Dashboard --}}
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                <p class="text-gray-600">Welcome, {{ $user->name }}!</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-6">
                <p class="text-gray-600">Your role: {{ ucfirst($user->role) }}</p>
            </div>
        @endif
    </div>
</x-layouts.app>
