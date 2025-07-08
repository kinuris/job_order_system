<x-layouts.app title="Edit Job Order">
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Edit Job Order #{{ $jobOrder->id }}</h1>
                <p class="text-gray-600 dark:text-gray-400">Update job order details and status</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.job-orders.show', $jobOrder) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Job Order
                </a>
            </div>
        </div>

        {{-- Job Order Form --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
            <form method="POST" action="{{ route('admin.job-orders.update', $jobOrder) }}" class="space-y-6">
                @csrf
                @method('PATCH')

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
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ (old('customer_id', $jobOrder->customer_id) == $customer->id) ? 'selected' : '' }}>
                                    {{ $customer->full_name }} - {{ $customer->email }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute right-2 top-2 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                    @error('customer_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Type, Status, and Priority --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Job Type <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 relative">
                            <select id="type" 
                                    name="type" 
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-500 @enderror"
                                    required>
                                @foreach(\App\Models\JobOrder::TYPES as $key => $label)
                                    <option value="{{ $key }}" {{ (old('type', $jobOrder->type) == $key) ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute right-2 top-2 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 relative">
                            <select id="status" 
                                    name="status" 
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror"
                                    required>
                                @foreach(\App\Models\JobOrder::STATUSES as $key => $label)
                                    <option value="{{ $key }}" {{ (old('status', $jobOrder->status) == $key) ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute right-2 top-2 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        @error('status')
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
                                @foreach(\App\Models\JobOrder::PRIORITIES as $key => $label)
                                    <option value="{{ $key }}" {{ (old('priority', $jobOrder->priority) == $key) ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute right-2 top-2 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Technician Assignment --}}
                <div>
                    <label for="technician_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Assigned Technician
                    </label>
                    <div class="mt-1 relative">
                        <select id="technician_id" 
                                name="technician_id" 
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('technician_id') border-red-500 @enderror">
                            <option value="">Unassigned</option>
                            @foreach($technicians as $technician)
                                <option value="{{ $technician->id }}" {{ (old('technician_id', $jobOrder->technician_id) == $technician->id) ? 'selected' : '' }}>
                                    {{ $technician->user->name }} - {{ $technician->specialty }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute right-2 top-2 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                    @error('technician_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Date Fields --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="scheduled_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Scheduled Date & Time
                        </label>
                        <input type="datetime-local" 
                               id="scheduled_at" 
                               name="scheduled_at" 
                               value="{{ old('scheduled_at', $jobOrder->scheduled_at ? $jobOrder->scheduled_at->format('Y-m-d\TH:i') : '') }}"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('scheduled_at') border-red-500 @enderror">
                        @error('scheduled_at')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="started_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Started Date & Time
                        </label>
                        <input type="datetime-local" 
                               id="started_at" 
                               name="started_at" 
                               value="{{ old('started_at', $jobOrder->started_at ? $jobOrder->started_at->format('Y-m-d\TH:i') : '') }}"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('started_at') border-red-500 @enderror">
                        @error('started_at')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="completed_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Completed Date & Time
                        </label>
                        <input type="datetime-local" 
                               id="completed_at" 
                               name="completed_at" 
                               value="{{ old('completed_at', $jobOrder->completed_at ? $jobOrder->completed_at->format('Y-m-d\TH:i') : '') }}"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('completed_at') border-red-500 @enderror">
                        @error('completed_at')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Job Description <span class="text-red-500">*</span>
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="4"
                              placeholder="Describe the work to be performed..."
                              class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                              required>{{ old('description', $jobOrder->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Resolution Notes --}}
                <div>
                    <label for="resolution_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Resolution Notes
                    </label>
                    <textarea id="resolution_notes" 
                              name="resolution_notes" 
                              rows="4"
                              placeholder="Add notes about the resolution or work performed..."
                              class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('resolution_notes') border-red-500 @enderror">{{ old('resolution_notes', $jobOrder->resolution_notes) }}</textarea>
                    @error('resolution_notes')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Document any work performed, parts used, or issues resolved.
                    </p>
                </div>

                {{-- Submit Buttons --}}
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.job-orders.show', $jobOrder) }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Job Order
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Auto-set timestamps based on status --}}
    <script>
        document.getElementById('status').addEventListener('change', function() {
            const status = this.value;
            const now = new Date().toISOString().slice(0, 16);
            
            if (status === 'in_progress') {
                const startedAt = document.getElementById('started_at');
                if (!startedAt.value) {
                    startedAt.value = now;
                }
            } else if (status === 'completed') {
                const completedAt = document.getElementById('completed_at');
                if (!completedAt.value) {
                    completedAt.value = now;
                }
            }
        });
    </script>
</x-layouts.app>
