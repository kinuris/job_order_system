<x-layouts.app title="Job Orders">
    <div class="space-y-4 sm:space-y-6 px-2 sm:px-0">
        {{-- Header - Mobile Optimized --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">Job Orders</h1>
                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400">Manage and track all job orders</p>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                {{-- Filter Dropdown - Mobile Friendly --}}
                <div class="relative">
                    <select id="status-filter" 
                            class="w-full sm:w-auto appearance-none bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 pr-10 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">All Statuses</option>
                        @foreach(\App\Models\JobOrder::STATUSES as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
                {{-- Add Job Order Button - Mobile Optimized --}}
                <a href="{{ route('admin.job-orders.create') }}" 
                   class="inline-flex items-center justify-center px-4 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 min-h-[44px]">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="hidden sm:inline">New Job Order</span>
                    <span class="sm:hidden">New Job</span>
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

        {{-- Job Orders Cards - Mobile Optimized --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg sm:rounded-xl p-3 sm:p-6">
            @if($jobOrders->count() > 0)
                {{-- Pagination --}}
                @if($jobOrders->hasPages())
                    <div class="mb-4">
                        {{ $jobOrders->links() }}
                    </div>
                @endif

                {{-- Mobile-Optimized Job Cards --}}
                <div class="space-y-3 sm:space-y-4">
                    @foreach($jobOrders as $jobOrder)
                        <div class="job-order-card border border-gray-200 dark:border-gray-600 rounded-lg p-3 sm:p-4 
                            @if($jobOrder->status === 'completed')
                                bg-green-50 dark:bg-green-900/10 border-green-200 dark:border-green-700
                            @elseif($jobOrder->status === 'cancelled')
                                bg-red-50 dark:bg-red-900/10 border-red-200 dark:border-red-700
                            @elseif($jobOrder->isLate())
                                border-red-300 dark:border-red-600 bg-red-50 dark:bg-red-900/10
                            @else
                                bg-gray-50 dark:bg-gray-700/50
                            @endif">
                            
                            {{-- Mobile-First Job Header --}}
                            <div class="space-y-2">
                                {{-- Top Row: Job ID and Status Badges --}}
                                <div class="flex items-start justify-between">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 text-base">Job #{{ $jobOrder->id }}</h4>
                                        @if($jobOrder->status === 'completed')
                                            <span class="px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                ✅ COMPLETED
                                            </span>
                                        @elseif($jobOrder->status === 'cancelled')
                                            <span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                                ❌ CANCELLED
                                            </span>
                                        @elseif($jobOrder->isLate())
                                            <span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 animate-pulse">
                                                {{ $jobOrder->getDaysLate() }}D LATE
                                            </span>
                                        @endif
                                    </div>
                                    
                                    {{-- Action Buttons - Always Visible on Mobile --}}
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.job-orders.show', $jobOrder) }}" 
                                           class="px-3 py-2 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition-colors min-h-[36px] flex items-center">
                                            <svg class="w-4 h-4 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            <span class="hidden sm:inline">View</span>
                                        </a>
                                        <a href="{{ route('admin.job-orders.edit', $jobOrder) }}" 
                                           class="px-3 py-2 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700 transition-colors min-h-[36px] flex items-center">
                                            <svg class="w-4 h-4 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            <span class="hidden sm:inline">Edit</span>
                                        </a>
                                        @if(in_array($jobOrder->status, ['pending_dispatch', 'cancelled']))
                                            <form method="POST" action="{{ route('admin.job-orders.destroy', $jobOrder) }}" 
                                                  class="inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this job order?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="px-3 py-2 bg-red-600 text-white text-xs rounded-lg hover:bg-red-700 transition-colors min-h-[36px] flex items-center">
                                                    <svg class="w-4 h-4 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    <span class="hidden sm:inline">Delete</span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                                
                                {{-- Customer and Priority Row --}}
                                <div class="flex flex-wrap items-center gap-2">
                                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $jobOrder->customer->full_name }}</p>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($jobOrder->priority === 'urgent') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                                        @elseif($jobOrder->priority === 'high') bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300
                                        @elseif($jobOrder->priority === 'medium') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                        @else bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 @endif">
                                        {{ \App\Models\JobOrder::PRIORITIES[$jobOrder->priority] }}
                                    </span>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($jobOrder->status === 'pending_dispatch') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                        @elseif($jobOrder->status === 'scheduled') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                        @elseif($jobOrder->status === 'en_route') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300
                                        @elseif($jobOrder->status === 'in_progress') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                        @elseif($jobOrder->status === 'on_hold') bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300
                                        @elseif($jobOrder->status === 'completed') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                        {{ \App\Models\JobOrder::STATUSES[$jobOrder->status] }}
                                    </span>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                        {{ \App\Models\JobOrder::TYPES[$jobOrder->type] }}
                                    </span>
                                </div>
                                
                                {{-- Customer Contact Info --}}
                                <div class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $jobOrder->customer->email }}</p>
                                </div>

                                {{-- Address --}}
                                <div class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <p class="text-base text-gray-800 dark:text-gray-200 leading-relaxed">{{ $jobOrder->customer->service_address }}</p>
                                </div>
                                
                                {{-- Technician Assignment --}}
                                <div class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    @if($jobOrder->technician)
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $jobOrder->technician->user->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $jobOrder->technician->specialty }}</p>
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Unassigned</p>
                                    @endif
                                </div>
                                
                                {{-- Date Information --}}
                                <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>Created: {{ $jobOrder->created_at->format('M d, Y \a\t g:i A') }}</span>
                                </div>
                            </div>

                            {{-- Job Details --}}
                            <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                                <div class="space-y-2">
                                    <div>
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Description</span>
                                        <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">{{ $jobOrder->description }}</p>
                                    </div>
                                    @if($jobOrder->resolution_notes)
                                        <div>
                                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Notes</span>
                                            <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">{{ $jobOrder->resolution_notes }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Bottom Pagination --}}
                @if($jobOrders->hasPages())
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $jobOrders->links() }}
                    </div>
                @endif
            @else
                {{-- Empty State --}}
                <div class="p-8 sm:p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No job orders</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating your first job order.</p>
                    <div class="mt-6">
                        <a href="{{ route('admin.job-orders.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            New Job Order
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Mobile-Optimized Styles --}}
    <style>
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
    </style>

    {{-- Enhanced Filter JavaScript --}}
    <script>
        document.getElementById('status-filter').addEventListener('change', function(e) {
            const selectedStatus = e.target.value.toLowerCase();
            const jobCards = document.querySelectorAll('.job-order-card');
            
            jobCards.forEach(card => {
                if (!selectedStatus) {
                    card.style.display = '';
                } else {
                    // Look for status badge in the card
                    const statusBadges = card.querySelectorAll('span');
                    let hasMatchingStatus = false;
                    
                    statusBadges.forEach(badge => {
                        const badgeText = badge.textContent.trim().toLowerCase();
                        const statusMap = {
                            'pending_dispatch': 'pending dispatch',
                            'scheduled': 'scheduled',
                            'en_route': 'en route',
                            'in_progress': 'in progress',
                            'on_hold': 'on hold',
                            'completed': 'completed',
                            'cancelled': 'cancelled'
                        };
                        
                        if (statusMap[selectedStatus] && badgeText.includes(statusMap[selectedStatus])) {
                            hasMatchingStatus = true;
                        }
                    });
                    
                    card.style.display = hasMatchingStatus ? '' : 'none';
                }
            });
        });
    </script>
</x-layouts.app>
