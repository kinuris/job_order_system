<x-layouts.app title="Job Orders">
    <div class="space-y-4 sm:space-y-6 px-2 sm:px-0">
        {{-- Header - Mobile Optimized --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">Job Orders</h1>
                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400">Manage and track all job orders</p>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                {{-- Status Filter --}}
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

                {{-- Priority Filter --}}
                <div class="relative">
                    <select id="priority-filter" 
                            class="w-full sm:w-auto appearance-none bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 pr-10 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">All Priorities</option>
                        @foreach(\App\Models\JobOrder::PRIORITIES as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>

                {{-- Date Range Filter --}}
                <div class="relative">
                    <select id="date-filter" 
                            class="w-full sm:w-auto appearance-none bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 pr-10 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">All Dates</option>
                        <option value="today">Today</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="overdue">Overdue</option>
                    </select>
                    <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>

                {{-- Assignment Filter --}}
                <div class="relative">
                    <select id="assignment-filter" 
                            class="w-full sm:w-auto appearance-none bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 pr-10 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">All Assignments</option>
                        <option value="assigned">Assigned</option>
                        <option value="unassigned">Unassigned</option>
                    </select>
                    <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>

                {{-- Clear Filters Button --}}
                <button id="clear-filters" 
                        class="inline-flex items-center justify-center px-4 py-3 bg-gray-500 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 min-h-[44px]">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span class="hidden sm:inline">Clear</span>
                </button>
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
                                        <button onclick="openChatModal({{ $jobOrder->id }})"
                                                class="px-3 py-2 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition-colors min-h-[36px] flex items-center relative">
                                            @if($jobOrder->unread_messages_count > 0)
                                                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-pulse">
                                                    {{ $jobOrder->unread_messages_count > 9 ? '9+' : $jobOrder->unread_messages_count }}
                                                </span>
                                            @endif
                                            <svg class="w-4 h-4 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.955 8.955 0 01-4.126-.98L3 20l1.98-5.874A8.955 8.955 0 013 12c0-4.418 3.582-8 8-8s8 3.582 8 8z"></path>
                                            </svg>
                                            <span class="hidden sm:inline">Chat</span>
                                        </button>
                                        <a href="{{ route('admin.job-orders.edit', $jobOrder) }}" 
                                           class="px-3 py-2 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700 transition-colors min-h-[36px] flex items-center">
                                            <svg class="w-4 h-4 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            <span class="hidden sm:inline">Edit</span>
                                        </a>
                                        @if(in_array($jobOrder->status, ['pending_dispatch', 'cancelled', 'completed']))
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 018 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
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

    {{-- Enhanced Chat Modal - Dashboard Style --}}
    <div id="chatModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 transition-all duration-300 hidden">
        <div class="flex items-end sm:items-center justify-center min-h-screen p-2 sm:p-4">
            <div class="bg-white dark:bg-gray-800 rounded-t-2xl sm:rounded-xl shadow-2xl w-full max-w-3xl h-[85vh] sm:h-[32rem] flex flex-col border border-gray-200 dark:border-gray-600 transform transition-all duration-300">
                
                {{-- Enhanced Chat Header --}}
                <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200 dark:border-gray-600 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-800 rounded-t-2xl sm:rounded-t-xl">
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a9.863 9.863 0 01-4.906-1.287A3 3 0 014 19h1a8 8 0 016-7.265M17 12a8 8 0 00-8-8 8 8 0 00-8 8"></path>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-gray-100 truncate">
                                Job Order #<span id="chatJobId">-</span> Chat
                            </h3>
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 hidden sm:block">Real-time communication</p>
                        </div>
                    </div>
                    <button onclick="closeChatModal()" class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Enhanced Messages Container --}}
                <div id="chatMessages" class="flex-1 overflow-y-auto p-3 sm:p-6 space-y-3 sm:space-y-4 bg-gray-50 dark:bg-gray-900/50">
                    <div class="flex justify-center items-center h-full">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    </div>
                </div>

                {{-- Enhanced Message Input --}}
                <div class="p-3 sm:p-6 border-t border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 rounded-b-2xl sm:rounded-b-xl">
                    <div class="flex gap-2 sm:gap-3">
                        <div class="flex-1 relative">
                            <input type="text" 
                                   id="messageInput" 
                                   placeholder="Type your message..." 
                                   class="w-full px-3 py-3 sm:px-4 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 pr-10 text-sm sm:text-base"
                                   onkeypress="handleMessageKeyPress(event)"
                                   required>
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                            </div>
                        </div>
                        <button onclick="sendMessage()" 
                                class="px-4 py-3 sm:px-6 sm:py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 flex items-center gap-2 font-medium flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            <span class="hidden sm:inline">Send</span>
                        </button>
                    </div>
                </div>
            </div>
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

        /* Enhanced Chat Modal Animations */
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Modal backdrop animation */
        #chatModal {
            animation: modalBackdropFadeIn 0.3s ease-out;
        }

        #chatModal.hidden {
            animation: modalBackdropFadeOut 0.3s ease-out;
        }

        @keyframes modalBackdropFadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes modalBackdropFadeOut {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }

        /* Message container smooth scrolling */
        #chatMessages {
            scroll-behavior: smooth;
        }

        /* Enhanced focus states for accessibility */
        .focus\:ring-blue-500:focus {
            --tw-ring-color: rgb(59 130 246 / 0.5);
        }

        /* Hover effects for better interaction feedback */
        .hover\:scale-105:hover {
            transform: scale(1.05);
        }

        /* Custom scrollbar for chat messages */
        #chatMessages::-webkit-scrollbar {
            width: 6px;
        }

        #chatMessages::-webkit-scrollbar-track {
            background: transparent;
        }

        #chatMessages::-webkit-scrollbar-thumb {
            background: rgb(156 163 175 / 0.5);
            border-radius: 3px;
        }

        #chatMessages::-webkit-scrollbar-thumb:hover {
            background: rgb(156 163 175 / 0.7);
        }

        /* Dark mode scrollbar */
        .dark #chatMessages::-webkit-scrollbar-thumb {
            background: rgb(75 85 99 / 0.5);
        }

        .dark #chatMessages::-webkit-scrollbar-thumb:hover {
            background: rgb(75 85 99 / 0.7);
        }
    </style>

    {{-- Enhanced Multi-Filter JavaScript --}}
    <script>
        // Filter state
        let currentFilters = {
            status: '',
            priority: '',
            date: '',
            assignment: ''
        };

        // Get current date helpers
        const today = new Date();
        const yesterday = new Date(today);
        yesterday.setDate(yesterday.getDate() - 1);
        const weekStart = new Date(today);
        weekStart.setDate(today.getDate() - today.getDay());
        const monthStart = new Date(today.getFullYear(), today.getMonth(), 1);

        // Date comparison helper
        function isDateInRange(dateString, range) {
            const date = new Date(dateString);
            const now = new Date();
            
            switch(range) {
                case 'today':
                    return date.toDateString() === today.toDateString();
                case 'yesterday':
                    return date.toDateString() === yesterday.toDateString();
                case 'week':
                    return date >= weekStart && date <= today;
                case 'month':
                    return date >= monthStart && date <= today;
                case 'overdue':
                    return date < today && !dateString.includes('completed');
                default:
                    return true;
            }
        }

        // Apply all filters
        function applyFilters() {
            const jobCards = document.querySelectorAll('.job-order-card');
            
            jobCards.forEach(card => {
                let shouldShow = true;
                
                // Status filter
                if (currentFilters.status) {
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
                        
                        if (statusMap[currentFilters.status] && badgeText.includes(statusMap[currentFilters.status])) {
                            hasMatchingStatus = true;
                        }
                    });
                    
                    if (!hasMatchingStatus) shouldShow = false;
                }
                
                // Priority filter
                if (currentFilters.priority && shouldShow) {
                    const priorityBadges = card.querySelectorAll('span');
                    let hasMatchingPriority = false;
                    
                    priorityBadges.forEach(badge => {
                        const badgeText = badge.textContent.trim().toLowerCase();
                        if (badgeText.includes(currentFilters.priority)) {
                            hasMatchingPriority = true;
                        }
                    });
                    
                    if (!hasMatchingPriority) shouldShow = false;
                }
                
                // Assignment filter
                if (currentFilters.assignment && shouldShow) {
                    const technicianSection = card.querySelector('svg + div, svg + p');
                    const isAssigned = technicianSection && !technicianSection.textContent.includes('Unassigned');
                    
                    if (currentFilters.assignment === 'assigned' && !isAssigned) shouldShow = false;
                    if (currentFilters.assignment === 'unassigned' && isAssigned) shouldShow = false;
                }
                
                // Date filter
                if (currentFilters.date && shouldShow) {
                    const dateText = card.querySelector('span:last-child');
                    if (dateText) {
                        const dateString = dateText.textContent;
                        
                        if (currentFilters.date === 'overdue') {
                            const hasLatebadge = card.querySelector('span.animate-pulse');
                            if (!hasLatebadge) shouldShow = false;
                        } else {
                            // Extract date from "Created: Mar 15, 2024 at 2:30 PM" format
                            const dateMatch = dateString.match(/Created: (.+)/);
                            if (dateMatch) {
                                const extractedDate = dateMatch[1].replace(' at ', ' ');
                                if (!isDateInRange(extractedDate, currentFilters.date)) {
                                    shouldShow = false;
                                }
                            }
                        }
                    }
                }
                
                card.style.display = shouldShow ? '' : 'none';
            });
            
            // Update results count
            updateResultsCount();
        }

        // Update results count
        function updateResultsCount() {
            const allCards = document.querySelectorAll('.job-order-card');
            const visibleCards = document.querySelectorAll('.job-order-card[style=""]');
            const hiddenCards = allCards.length - visibleCards.length;
            
            // Only show filter results indicator when filters are actually applied
            const hasActiveFilters = currentFilters.status || currentFilters.priority || currentFilters.date || currentFilters.assignment;
            
            // Add or update results indicator
            let resultsIndicator = document.getElementById('filter-results');
            if (!resultsIndicator) {
                resultsIndicator = document.createElement('div');
                resultsIndicator.id = 'filter-results';
                resultsIndicator.className = 'text-sm text-blue-600 dark:text-blue-400 mb-4 px-3 font-medium';
                const cardContainer = document.querySelector('.space-y-3');
                if (cardContainer) {
                    cardContainer.parentNode.insertBefore(resultsIndicator, cardContainer);
                }
            }
            
            // Only show when filters are active and hiding some results
            if (hasActiveFilters && hiddenCards > 0) {
                resultsIndicator.textContent = `Filtered: ${visibleCards.length} of ${allCards.length} job orders shown`;
                resultsIndicator.style.display = 'block';
            } else {
                resultsIndicator.style.display = 'none';
            }
        }

        // Event listeners for filters
        document.getElementById('status-filter').addEventListener('change', function(e) {
            currentFilters.status = e.target.value.toLowerCase();
            applyFilters();
        });

        document.getElementById('priority-filter').addEventListener('change', function(e) {
            currentFilters.priority = e.target.value.toLowerCase();
            applyFilters();
        });

        document.getElementById('date-filter').addEventListener('change', function(e) {
            currentFilters.date = e.target.value;
            applyFilters();
        });

        document.getElementById('assignment-filter').addEventListener('change', function(e) {
            currentFilters.assignment = e.target.value;
            applyFilters();
        });

        // Clear all filters
        document.getElementById('clear-filters').addEventListener('click', function() {
            // Reset all filter values
            document.getElementById('status-filter').value = '';
            document.getElementById('priority-filter').value = '';
            document.getElementById('date-filter').value = '';
            document.getElementById('assignment-filter').value = '';
            
            // Reset filter state
            currentFilters = {
                status: '',
                priority: '',
                date: '',
                assignment: ''
            };
            
            // Show all cards
            const jobCards = document.querySelectorAll('.job-order-card');
            jobCards.forEach(card => {
                card.style.display = '';
            });
            
            // Hide results indicator
            const resultsIndicator = document.getElementById('filter-results');
            if (resultsIndicator) {
                resultsIndicator.style.display = 'none';
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            applyFilters();
        });

        // Chat functionality
        let currentChatJobId = null;
        let chatRefreshInterval = null;

        function openChatModal(jobId) {
            currentChatJobId = jobId;
            document.getElementById('chatJobId').textContent = jobId;
            
            const modal = document.getElementById('chatModal');
            modal.classList.remove('hidden');
            
            // Trigger reflow to ensure the transition works
            modal.offsetHeight;
            
            document.getElementById('messageInput').value = '';
            
            // Load messages
            loadChatMessages();
            
            // Start auto-refresh (every 3 seconds)
            if (chatRefreshInterval) {
                clearInterval(chatRefreshInterval);
            }
            chatRefreshInterval = setInterval(loadChatMessages, 3000);
            
            // Focus message input with slight delay for better UX
            setTimeout(() => {
                document.getElementById('messageInput').focus();
            }, 300);
            
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
        }

        function closeChatModal() {
            const modal = document.getElementById('chatModal');
            const jobId = currentChatJobId;
            
            // Add closing animation class
            modal.style.animation = 'modalBackdropFadeOut 0.3s ease-out';
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.style.animation = '';
                currentChatJobId = null;
                
                // Restore body scroll
                document.body.style.overflow = '';
                
                // Clear auto-refresh
                if (chatRefreshInterval) {
                    clearInterval(chatRefreshInterval);
                    chatRefreshInterval = null;
                }
                
                // Update the unread message badge for this job order
                if (jobId) {
                    updateUnreadBadge(jobId);
                }
            }, 300);
        }

        function updateUnreadBadge(jobId) {
            // Find the chat button for this job and remove its badge since we just read the messages
            const chatButtons = document.querySelectorAll(`button[onclick="openChatModal(${jobId})"]`);
            chatButtons.forEach(button => {
                const badge = button.querySelector('.absolute');
                if (badge) {
                    badge.remove();
                }
            });
        }

        function loadChatMessages() {
            if (!currentChatJobId) return;

            fetch(`/job-orders/${currentChatJobId}/messages`)
                .then(response => response.json())
                .then(messages => {
                    const container = document.getElementById('chatMessages');
                    
                    if (messages.length === 0) {
                        container.innerHTML = `
                            <div class="text-center py-8 sm:py-12">
                                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a9.863 9.863 0 01-4.906-1.287A3 3 0 014 19h1a8 8 0 016-7.265M17 12a8 8 0 00-8-8 8 8 0 00-8 8"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-500 dark:text-gray-400 text-base sm:text-lg">No messages yet</p>
                                <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Start the conversation!</p>
                            </div>
                        `;
                        return;
                    }

                    container.innerHTML = messages.map(message => {
                        const isOwnMessage = message.is_own_message;
                        const userRole = message.user_role;
                        const userName = message.user_name;
                        const userInitial = userName.charAt(0).toUpperCase();
                        
                        const isAdmin = userRole === 'admin';
                        const roleColorClass = isAdmin 
                            ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' 
                            : 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400';
                        
                        const userNameColorClass = isAdmin 
                            ? 'text-blue-600 dark:text-blue-400' 
                            : 'text-green-600 dark:text-green-400';
                        
                        return `
                            <div class="flex ${isOwnMessage ? 'justify-end' : 'justify-start'} animate-fade-in">
                                <div class="max-w-[85%] sm:max-w-sm lg:max-w-md">
                                    ${!isOwnMessage ? `
                                        <div class="flex items-center mb-1 ml-1">
                                            <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-full ${roleColorClass} flex items-center justify-center mr-2">
                                                <span class="text-xs font-semibold">
                                                    ${userInitial}
                                                </span>
                                            </div>
                                            <span class="text-xs font-medium ${userNameColorClass} truncate">
                                                ${escapeHtml(userName)}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-2 px-1.5 py-0.5 bg-gray-100 dark:bg-gray-700 rounded-full hidden sm:inline">
                                                ${userRole.charAt(0).toUpperCase() + userRole.slice(1)}
                                            </span>
                                        </div>
                                    ` : ''}
                                    <div class="px-3 py-2.5 sm:px-4 sm:py-3 rounded-2xl shadow-sm ${isOwnMessage 
                                        ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-br-md' 
                                        : 'bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-900 dark:text-gray-100 rounded-bl-md'}">
                                        <div class="text-sm leading-relaxed whitespace-pre-wrap">${escapeHtml(message.message)}</div>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 ${isOwnMessage ? 'text-right mr-1' : 'ml-1'}">
                                        ${message.created_at}
                                    </div>
                                </div>
                            </div>
                        `;
                    }).join('');

                    // Mark messages as read
                    markMessagesAsRead();
                    
                    // Scroll to bottom with smooth animation
                    setTimeout(() => {
                        container.scrollTop = container.scrollHeight;
                    }, 100);
                })
                .catch(error => {
                    console.error('Error loading messages:', error);
                    const container = document.getElementById('chatMessages');
                    container.innerHTML = `
                        <div class="text-center py-8">
                            <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-red-500 text-sm">Error loading messages. Please try again.</p>
                        </div>
                    `;
                });
        }

        function sendMessage() {
            const input = document.getElementById('messageInput');
            const message = input.value.trim();
            
            if (!message || !currentChatJobId) return;

            // Get the send button for loading state
            const sendButton = input.parentElement.parentElement.querySelector('button[onclick="sendMessage()"]');
            const originalButtonContent = sendButton.innerHTML;
            
            // Disable input and show loading state
            input.disabled = true;
            sendButton.disabled = true;
            sendButton.innerHTML = `
                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                <span class="hidden sm:inline">Sending...</span>
            `;
            
            fetch(`/job-orders/${currentChatJobId}/messages`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ message: message })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    // Show error with better styling
                    showToast('Error sending message: ' + data.error, 'error');
                    return;
                }
                
                input.value = '';
                loadChatMessages();
                
                // Show success feedback
                showToast('Message sent!', 'success');
            })
            .catch(error => {
                console.error('Error sending message:', error);
                showToast('Error sending message. Please try again.', 'error');
            })
            .finally(() => {
                input.disabled = false;
                sendButton.disabled = false;
                sendButton.innerHTML = originalButtonContent;
                input.focus();
            });
        }

        // Toast notification system
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-[60] px-4 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                'bg-blue-500 text-white'
            }`;
            toast.textContent = message;
            
            document.body.appendChild(toast);
            
            // Trigger animation
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);
            
            // Remove after 3 seconds
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 3000);
        }

        function markMessagesAsRead() {
            if (!currentChatJobId) return;

            fetch(`/job-orders/${currentChatJobId}/messages/mark-read`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).catch(error => {
                console.error('Error marking messages as read:', error);
            });
        }

        function handleMessageKeyPress(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                sendMessage();
            }
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Close modal when clicking outside
        document.getElementById('chatModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeChatModal();
            }
        });

        // Close modal on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && !document.getElementById('chatModal').classList.contains('hidden')) {
                closeChatModal();
            }
        });
    </script>
</x-layouts.app>
