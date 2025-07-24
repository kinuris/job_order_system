<x-layouts.app title="Plan Details">
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $plan->name }}</h1>
                <p class="text-gray-600 dark:text-gray-400">Plan details and customer information</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.plans.edit', $plan) }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Plan
                </a>
                <a href="{{ route('admin.plans.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Plans
                </a>
            </div>
        </div>

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-md">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column: Plan Details --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Plan Information --}}
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Plan Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Plan Name</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $plan->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Plan Type</label>
                            <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($plan->type === 'internet') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                @elseif($plan->type === 'cable') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                @elseif($plan->type === 'phone') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @else bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200 @endif">
                                {{ $plan->getTypeLabel() }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Monthly Rate</label>
                            <p class="mt-1 text-lg font-bold text-gray-900 dark:text-gray-100">{{ $plan->formatted_monthly_rate }}</p>
                        </div>

                        @if($plan->speed_mbps)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Speed</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $plan->speed_mbps }} Mbps</p>
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                            @if($plan->is_active)
                                <span class="mt-1 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Active
                                </span>
                            @else
                                <span class="mt-1 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                    Inactive
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($plan->description)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Description</label>
                            <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $plan->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Right Column: Statistics --}}
            <div class="space-y-6">
                {{-- Plan Statistics --}}
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Statistics</h2>
                    
                    <div class="space-y-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $plan->customers->count() }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Total Customers</div>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-2 text-center">
                            <div>
                                <div class="text-lg font-semibold text-green-600 dark:text-green-400">{{ $plan->customers->where('plan_status', 'active')->count() }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Active</div>
                            </div>
                            <div>
                                <div class="text-lg font-semibold text-yellow-600 dark:text-yellow-400">{{ $plan->customers->where('plan_status', 'suspended')->count() }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Suspended</div>
                            </div>
                            <div>
                                <div class="text-lg font-semibold text-red-600 dark:text-red-400">{{ $plan->customers->where('plan_status', 'inactive')->count() }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Inactive</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Actions</h2>
                    
                    <div class="space-y-3">
                        <a href="{{ route('admin.plans.edit', $plan) }}" 
                           class="block w-full text-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Edit Plan
                        </a>
                        
                        @if($plan->customers->count() === 0)
                            <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" 
                                  onsubmit="return confirm('Are you sure you want to delete this plan? This action cannot be undone.')" 
                                  class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="block w-full text-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Delete Plan
                                </button>
                            </form>
                        @else
                            <div class="text-center">
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Cannot delete plan with active customers</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                    {{ $plan->customers->count() }} customers assigned
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Customers Table --}}
        @if($plan->customers->count() > 0)
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Customers on this Plan</h2>
                
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-600">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Customer</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Contact</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Installed</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                            @foreach($plan->customers as $customer)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $customer->full_name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">Customer #{{ $customer->id }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $customer->email }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $customer->phone_number }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $customer->plan_installed_at ? $customer->plan_installed_at->format('M d, Y') : 'Not set' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($customer->plan_status === 'active') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @elseif($customer->plan_status === 'suspended') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                            {{ $customer->getPlanStatusLabel() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.customers.show', $customer) }}" 
                                           class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200">
                                            View Customer
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
