<x-layouts.app title="Create Job Order">
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Create New Job Order</h1>
                <p class="text-gray-600 dark:text-gray-400">Create a new job order for a customer</p>
            </div>
            <a href="{{ route('admin.job-orders.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Job Orders
            </a>
        </div>

        {{-- Job Order Form --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
            <form method="POST" action="{{ route('admin.job-orders.store') }}" class="space-y-6">
                @csrf

                {{-- Customer Selection --}}
                <div>
                    <label for="customer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Customer <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 relative">
                        <select id="customer_id" 
                                name="customer_id" 
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('customer_id') border-red-500 @enderror"
                                required>
                            <option value="">Select a customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->full_name }} - {{ $customer->email }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('customer_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Don't see the customer? <a href="{{ route('admin.customers.create') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">Create a new customer</a> first.
                    </p>
                </div>

                {{-- Type and Priority --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Job Type <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 relative">
                            <select id="type" 
                                    name="type" 
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-500 @enderror"
                                    required>
                                <option value="">Select job type</option>
                                @foreach(\App\Models\JobOrder::TYPES as $key => $label)
                                    <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Priority <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 relative">
                            <select id="priority" 
                                    name="priority" 
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('priority') border-red-500 @enderror"
                                    required>
                                <option value="">Select priority</option>
                                @foreach(\App\Models\JobOrder::PRIORITIES as $key => $label)
                                    <option value="{{ $key }}" {{ old('priority') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Technician Assignment (Optional) --}}
                <div>
                    <label for="technician_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Assign Technician (Optional)
                    </label>
                    <div class="mt-1 relative">
                        <select id="technician_id" 
                                name="technician_id" 
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('technician_id') border-red-500 @enderror">
                            <option value="">Assign later</option>
                            @foreach($technicians as $technician)
                                <option value="{{ $technician->id }}" {{ old('technician_id') == $technician->id ? 'selected' : '' }}>
                                    {{ $technician->user->name }} - {{ $technician->specialty }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('technician_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        You can assign a technician now or later from the job order details page.
                    </p>
                </div>

                {{-- Scheduled Date/Time (Optional) --}}
                <div>
                    <label for="scheduled_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Scheduled Date & Time (Optional)
                    </label>
                    <input type="datetime-local" 
                           id="scheduled_at" 
                           name="scheduled_at" 
                           value="{{ old('scheduled_at') }}"
                           min="{{ now()->format('Y-m-d\TH:i') }}"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('scheduled_at') border-red-500 @enderror">
                    @error('scheduled_at')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Leave blank to schedule later.
                    </p>
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Job Description <span class="text-red-500">*</span>
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="4"
                              placeholder="Describe the work to be performed, including any specific requirements or customer requests..."
                              class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                              required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit Buttons --}}
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.job-orders.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Create Job Order
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- JavaScript for dynamic interactions --}}
    <script>
        // Auto-populate customer info when selected
        document.getElementById('customer_id').addEventListener('change', function() {
            const customerId = this.value;
            if (customerId) {
                // You could add AJAX call here to load customer details
                console.log('Selected customer:', customerId);
            }
        });
    </script>
</x-layouts.app>
