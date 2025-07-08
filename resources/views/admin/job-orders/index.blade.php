<x-layouts.app title="Job Orders">
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Job Orders</h1>
                <p class="text-gray-600 dark:text-gray-400">Manage and track all job orders</p>
            </div>
            <div class="flex items-center gap-3">
                {{-- Filter Dropdown --}}
                <div class="relative">
                    <select id="status-filter" 
                            class="appearance-none bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 pr-8 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Statuses</option>
                        @foreach(\App\Models\JobOrder::STATUSES as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <svg class="absolute right-2 top-3 h-4 w-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
                {{-- Add Job Order Button --}}
                <a href="{{ route('admin.job-orders.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Job Order
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

        {{-- Job Orders Table --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
            @if($jobOrders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Job Order
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Customer
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Type & Priority
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Technician
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Created
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($jobOrders as $jobOrder)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                #{{ $jobOrder->id }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                                {{ Str::limit($jobOrder->description, 50) }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $jobOrder->customer->full_name }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $jobOrder->customer->email }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="space-y-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                {{ \App\Models\JobOrder::TYPES[$jobOrder->type] }}
                                            </span>
                                            <div>
                                                @php
                                                    $priorityColors = [
                                                        'low' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
                                                        'medium' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                                        'high' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
                                                        'urgent' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                                    ];
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $priorityColors[$jobOrder->priority] }}">
                                                    {{ \App\Models\JobOrder::PRIORITIES[$jobOrder->priority] }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
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
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$jobOrder->status] }}">
                                            {{ \App\Models\JobOrder::STATUSES[$jobOrder->status] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($jobOrder->technician)
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $jobOrder->technician->user->name }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $jobOrder->technician->specialty }}
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-500 dark:text-gray-400">Unassigned</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $jobOrder->created_at->format('M d, Y') }}
                                        <div>{{ $jobOrder->created_at->format('g:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.job-orders.show', $jobOrder) }}" 
                                               class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                View
                                            </a>
                                            <a href="{{ route('admin.job-orders.edit', $jobOrder) }}" 
                                               class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                                Edit
                                            </a>
                                            @if(in_array($jobOrder->status, ['pending_dispatch', 'cancelled']))
                                                <form method="POST" action="{{ route('admin.job-orders.destroy', $jobOrder) }}" 
                                                      class="inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this job order?')">
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

                {{-- Pagination --}}
                @if($jobOrders->hasPages())
                    <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700">
                        {{ $jobOrders->links() }}
                    </div>
                @endif
            @else
                {{-- Empty State --}}
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No job orders</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating your first job order.</p>
                    <div class="mt-6">
                        <a href="{{ route('admin.job-orders.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
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

    {{-- Filter JavaScript --}}
    <script>
        document.getElementById('status-filter').addEventListener('change', function(e) {
            const status = e.target.value;
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                if (!status) {
                    row.style.display = '';
                } else {
                    const statusCell = row.children[3];
                    const statusText = statusCell.textContent.trim().toLowerCase();
                    const targetStatus = '{{ \App\Models\JobOrder::STATUSES["' + status + '"] ?? "" }}'.toLowerCase();
                    row.style.display = statusText.includes(targetStatus) ? '' : 'none';
                }
            });
        });
    </script>
</x-layouts.app>
