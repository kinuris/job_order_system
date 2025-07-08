<x-layouts.app title="Job Order Details">
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Job Order #{{ $jobOrder->id }}</h1>
                <p class="text-gray-600 dark:text-gray-400">Created on {{ $jobOrder->created_at->format('M d, Y \a\t g:i A') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.job-orders.edit', $jobOrder) }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Job Order
                </a>
                <a href="{{ route('admin.job-orders.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Job Orders
                </a>
            </div>
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column: Job Details --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Job Information --}}
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Job Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Job Type</label>
                            <p class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ \App\Models\JobOrder::TYPES[$jobOrder->type] }}
                                </span>
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Priority</label>
                            <p class="mt-1">
                                @php
                                    $priorityColors = [
                                        'low' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
                                        'medium' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                        'high' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
                                        'urgent' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium {{ $priorityColors[$jobOrder->priority] }}">
                                    {{ \App\Models\JobOrder::PRIORITIES[$jobOrder->priority] }}
                                </span>
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Current Status</label>
                            <p class="mt-1">
                                @php
                                    $statusColors = [
                                        'pending_dispatch' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                        'scheduled' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                        'en_route' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
                                        'in_progress' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200',
                                        'on_hold' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
                                        'completed' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                        'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium {{ $statusColors[$jobOrder->status] }}">
                                    {{ \App\Models\JobOrder::STATUSES[$jobOrder->status] }}
                                </span>
                            </p>
                        </div>

                        @if($jobOrder->scheduled_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Scheduled Date</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $jobOrder->scheduled_at->format('M d, Y \a\t g:i A') }}
                                </p>
                            </div>
                        @endif

                        @if($jobOrder->started_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Started At</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $jobOrder->started_at->format('M d, Y \a\t g:i A') }}
                                </p>
                            </div>
                        @endif

                        @if($jobOrder->completed_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Completed At</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $jobOrder->completed_at->format('M d, Y \a\t g:i A') }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Job Description --}}
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Job Description</h2>
                    <div class="prose prose-sm max-w-none dark:prose-invert">
                        <p class="text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $jobOrder->description }}</p>
                    </div>
                </div>

                {{-- Resolution Notes (if completed) --}}
                @if($jobOrder->resolution_notes)
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Resolution Notes</h2>
                        <div class="prose prose-sm max-w-none dark:prose-invert">
                            <p class="text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $jobOrder->resolution_notes }}</p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Right Column: Customer & Technician Info + Actions --}}
            <div class="space-y-6">
                {{-- Customer Information --}}
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Customer</h2>
                    
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $jobOrder->customer->full_name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Customer #{{ $jobOrder->customer->id }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-900 dark:text-gray-100">
                                <a href="mailto:{{ $jobOrder->customer->email }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                    {{ $jobOrder->customer->email }}
                                </a>
                            </p>
                            <p class="text-sm text-gray-900 dark:text-gray-100">
                                <a href="tel:{{ $jobOrder->customer->phone_number }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                    {{ $jobOrder->customer->phone_number }}
                                </a>
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Service Address</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $jobOrder->customer->service_address }}</p>
                        </div>

                        <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('admin.customers.show', $jobOrder->customer) }}" 
                               class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                View Customer Profile →
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Technician Information --}}
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Assigned Technician</h2>
                    
                    @if($jobOrder->technician)
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $jobOrder->technician->user->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $jobOrder->technician->specialty }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-900 dark:text-gray-100">
                                    <a href="mailto:{{ $jobOrder->technician->user->email }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                        {{ $jobOrder->technician->user->email }}
                                    </a>
                                </p>
                                @if($jobOrder->technician->phone_number)
                                    <p class="text-sm text-gray-900 dark:text-gray-100">
                                        <a href="tel:{{ $jobOrder->technician->phone_number }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                            {{ $jobOrder->technician->phone_number }}
                                        </a>
                                    </p>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No technician assigned</p>
                        </div>
                    @endif
                </div>

                {{-- Quick Actions --}}
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Quick Actions</h2>
                    
                    <div class="space-y-3">
                        {{-- Assign Technician --}}
                        @if(!$jobOrder->technician)
                            <form method="POST" action="{{ route('admin.job-orders.assign-technician', $jobOrder) }}">
                                @csrf
                                @method('PATCH')
                                <select name="technician_id" 
                                        class="block w-full text-sm px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 mb-2"
                                        required>
                                    <option value="">Select technician</option>
                                    @foreach(\App\Models\Technician::with('user')->get() as $technician)
                                        <option value="{{ $technician->id }}">
                                            {{ $technician->user->name }} - {{ $technician->specialty }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" 
                                        class="w-full inline-flex justify-center items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                    Assign Technician
                                </button>
                            </form>
                        @endif

                        {{-- Update Status --}}
                        <form method="POST" action="{{ route('admin.job-orders.update-status', $jobOrder) }}">
                            @csrf
                            @method('PATCH')
                            <select name="status" 
                                    class="block w-full text-sm px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 mb-2">
                                @foreach(\App\Models\JobOrder::STATUSES as $key => $label)
                                    <option value="{{ $key }}" {{ $jobOrder->status === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" 
                                    class="w-full inline-flex justify-center items-center px-3 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                Update Status
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
