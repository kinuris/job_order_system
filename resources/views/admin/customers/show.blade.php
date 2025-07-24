<x-layouts.app title="Customer Details">
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $customer->full_name }}</h1>
                <p class="text-gray-600 dark:text-gray-400">Customer #{{ $customer->id }} • Member since {{ $customer->created_at->format('M d, Y') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.customers.edit', $customer) }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Customer
                </a>
                <a href="{{ route('admin.customers.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Customers
                </a>
            </div>
        </div>

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Customer Information --}}
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Customer Information</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Full Name</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $customer->full_name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Email Address</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                <a href="mailto:{{ $customer->email }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">
                                    {{ $customer->email }}
                                </a>
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Phone Number</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                <a href="tel:{{ $customer->phone_number }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">
                                    {{ $customer->phone_number }}
                                </a>
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Service Address</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 whitespace-pre-line">{{ $customer->service_address }}</p>
                        </div>

                        {{-- Plan Information --}}
                        @if($customer->plan)
                            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Service Plan</label>
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 space-y-3">
                                    <div class="flex items-center justify-between">
                                        <h4 class="font-medium text-gray-900 dark:text-gray-100">{{ $customer->plan->name }}</h4>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($customer->plan_status === 'active') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @elseif($customer->plan_status === 'suspended') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                            {{ $customer->getPlanStatusLabel() }}
                                        </span>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <span class="text-gray-600 dark:text-gray-400">Type:</span>
                                            <span class="ml-1 font-medium text-gray-900 dark:text-gray-100">{{ $customer->plan->getTypeLabel() }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600 dark:text-gray-400">Monthly Rate:</span>
                                            <span class="ml-1 font-medium text-gray-900 dark:text-gray-100">{{ $customer->plan->formatted_monthly_rate }}</span>
                                        </div>
                                        @if($customer->plan->speed_mbps)
                                            <div>
                                                <span class="text-gray-600 dark:text-gray-400">Speed:</span>
                                                <span class="ml-1 font-medium text-gray-900 dark:text-gray-100">{{ $customer->plan->speed_mbps }} Mbps</span>
                                            </div>
                                        @endif
                                        @if($customer->plan_installed_at)
                                            <div>
                                                <span class="text-gray-600 dark:text-gray-400">Installed:</span>
                                                <span class="ml-1 font-medium text-gray-900 dark:text-gray-100">{{ $customer->plan_installed_at->format('M d, Y') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    @if($customer->plan->description)
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ $customer->plan->description }}</p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Service Plan</label>
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 text-center">
                                    <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">No service plan assigned</p>
                                    <a href="{{ route('admin.customers.edit', $customer) }}" 
                                       class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">
                                        Assign a plan →
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="grid grid-cols-2 gap-4 text-center">
                                <div>
                                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $customer->jobOrders->count() }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Total Jobs</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $customer->jobOrders->where('status', 'completed')->count() }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Completed</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Job Orders --}}
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Job Orders</h2>
                        <a href="{{ route('admin.job-orders.create', ['customer_id' => $customer->id]) }}" 
                           class="inline-flex items-center px-3 py-1.5 bg-blue-600 border border-transparent rounded-md text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            New Job Order
                        </a>
                    </div>

                    @if($customer->jobOrders->count() > 0)
                        <div class="space-y-4">
                            @foreach($customer->jobOrders->sortByDesc('created_at') as $jobOrder)
                                <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <h3 class="font-medium text-gray-900 dark:text-gray-100">
                                                    Job Order #{{ $jobOrder->id }}
                                                </h3>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                    @if($jobOrder->status === 'pending_dispatch') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                    @elseif($jobOrder->status === 'scheduled') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                    @elseif($jobOrder->status === 'in_progress') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @elseif($jobOrder->status === 'completed') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                                    @elseif($jobOrder->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                    @else bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $jobOrder->status)) }}
                                                </span>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                    @if($jobOrder->priority === 'urgent') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                    @elseif($jobOrder->priority === 'high') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                                    @elseif($jobOrder->priority === 'medium') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                    @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @endif">
                                                    {{ ucfirst($jobOrder->priority) }}
                                                </span>
                                            </div>
                                            
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                                <strong>Type:</strong> {{ ucfirst($jobOrder->type) }} • 
                                                <strong>Created:</strong> {{ $jobOrder->created_at->format('M d, Y') }}
                                                @if($jobOrder->scheduled_at)
                                                    • <strong>Scheduled:</strong> {{ $jobOrder->scheduled_at->format('M d, Y H:i') }}
                                                @endif
                                            </p>
                                            
                                            <p class="text-sm text-gray-900 dark:text-gray-100 mb-2">{{ $jobOrder->description }}</p>
                                            
                                            @if($jobOrder->technician)
                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                    <strong>Technician:</strong> {{ $jobOrder->technician->full_name }}
                                                </p>
                                            @endif
                                        </div>
                                        
                                        <div class="ml-4">
                                            <a href="{{ route('admin.job-orders.show', $jobOrder) }}" 
                                               class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 text-sm">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No job orders</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">This customer doesn't have any job orders yet.</p>
                            <div class="mt-6">
                                <a href="{{ route('admin.job-orders.create', ['customer_id' => $customer->id]) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Create First Job Order
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
