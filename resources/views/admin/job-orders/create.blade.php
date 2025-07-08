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
                    <div class="mt-1 relative" id="customer-dropdown-container">
                        {{-- Hidden input to store the selected customer ID --}}
                        <input type="hidden" id="customer_id" name="customer_id" value="{{ old('customer_id') }}">
                        
                        {{-- Search input --}}
                        <input type="text" 
                               id="customer-search" 
                               placeholder="Search customers by name or address..."
                               class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('customer_id') border-red-500 @enderror"
                               autocomplete="off">
                        
                        {{-- Dropdown list --}}
                        <div id="customer-dropdown" 
                             class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg hidden max-h-60 overflow-y-auto">
                            <div class="py-1">
                                <div id="no-customers" class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400 hidden">
                                    No customers found
                                </div>
                                @foreach($customers as $customer)
                                    <div class="customer-option px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer border-b border-gray-100 dark:border-gray-600 last:border-b-0" 
                                         data-customer-id="{{ $customer->id }}"
                                         data-customer-name="{{ $customer->full_name }}"
                                         data-customer-address="{{ $customer->service_address }}">
                                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $customer->full_name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $customer->service_address }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        {{-- Selected customer display --}}
                        <div id="selected-customer" class="hidden">
                            <div class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-md">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-medium text-blue-900 dark:text-blue-100" id="selected-customer-name"></div>
                                        <div class="text-sm text-blue-600 dark:text-blue-300" id="selected-customer-address"></div>
                                    </div>
                                    <button type="button" 
                                            id="clear-customer" 
                                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
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

    {{-- JavaScript for searchable customer dropdown and form interactions --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const customerSearch = document.getElementById('customer-search');
            const customerDropdown = document.getElementById('customer-dropdown');
            const customerIdInput = document.getElementById('customer_id');
            const selectedCustomerDiv = document.getElementById('selected-customer');
            const selectedCustomerName = document.getElementById('selected-customer-name');
            const selectedCustomerAddress = document.getElementById('selected-customer-address');
            const clearCustomerBtn = document.getElementById('clear-customer');
            const noCustomersDiv = document.getElementById('no-customers');
            const customerOptions = document.querySelectorAll('.customer-option');

            // Show dropdown when search input is focused
            customerSearch.addEventListener('focus', function() {
                showDropdown();
            });

            // Hide dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.closest('#customer-dropdown-container')) {
                    hideDropdown();
                }
            });

            // Filter customers as user types
            customerSearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                let hasVisibleOptions = false;

                customerOptions.forEach(option => {
                    const customerName = option.dataset.customerName.toLowerCase();
                    const customerAddress = option.dataset.customerAddress.toLowerCase();
                    
                    if (customerName.includes(searchTerm) || customerAddress.includes(searchTerm)) {
                        option.style.display = 'block';
                        hasVisibleOptions = true;
                    } else {
                        option.style.display = 'none';
                    }
                });

                // Show/hide "no customers found" message
                if (hasVisibleOptions) {
                    noCustomersDiv.classList.add('hidden');
                } else {
                    noCustomersDiv.classList.remove('hidden');
                }

                showDropdown();
            });

            // Handle customer selection
            customerOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const customerId = this.dataset.customerId;
                    const customerName = this.dataset.customerName;
                    const customerAddress = this.dataset.customerAddress;

                    // Set the hidden input value
                    customerIdInput.value = customerId;
                    
                    // Update search input to show selected customer
                    customerSearch.value = customerName;
                    
                    // Show selected customer details
                    selectedCustomerName.textContent = customerName;
                    selectedCustomerAddress.textContent = customerAddress;
                    selectedCustomerDiv.classList.remove('hidden');
                    
                    // Hide the search input and dropdown
                    customerSearch.style.display = 'none';
                    hideDropdown();
                });
            });

            // Handle clear customer selection
            clearCustomerBtn.addEventListener('click', function() {
                customerIdInput.value = '';
                customerSearch.value = '';
                customerSearch.style.display = 'block';
                selectedCustomerDiv.classList.add('hidden');
                customerSearch.focus();
            });

            // Show dropdown
            function showDropdown() {
                customerDropdown.classList.remove('hidden');
            }

            // Hide dropdown
            function hideDropdown() {
                customerDropdown.classList.add('hidden');
            }

            // Handle keyboard navigation
            customerSearch.addEventListener('keydown', function(event) {
                const visibleOptions = Array.from(customerOptions).filter(option => 
                    option.style.display !== 'none'
                );
                
                if (event.key === 'ArrowDown') {
                    event.preventDefault();
                    if (visibleOptions.length > 0) {
                        visibleOptions[0].focus();
                    }
                } else if (event.key === 'Escape') {
                    hideDropdown();
                }
            });

            // Handle keyboard navigation in dropdown options
            customerOptions.forEach((option, index) => {
                option.setAttribute('tabindex', '0');
                
                option.addEventListener('keydown', function(event) {
                    const visibleOptions = Array.from(customerOptions).filter(opt => 
                        opt.style.display !== 'none'
                    );
                    const currentIndex = visibleOptions.indexOf(this);
                    
                    if (event.key === 'ArrowDown') {
                        event.preventDefault();
                        const nextIndex = (currentIndex + 1) % visibleOptions.length;
                        visibleOptions[nextIndex].focus();
                    } else if (event.key === 'ArrowUp') {
                        event.preventDefault();
                        if (currentIndex === 0) {
                            customerSearch.focus();
                        } else {
                            const prevIndex = currentIndex - 1;
                            visibleOptions[prevIndex].focus();
                        }
                    } else if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        this.click();
                    } else if (event.key === 'Escape') {
                        hideDropdown();
                        customerSearch.focus();
                    }
                });
            });

            // Initialize: if there's an old value, select it
            const oldCustomerId = "{{ old('customer_id') }}";
            if (oldCustomerId) {
                const selectedOption = document.querySelector(`[data-customer-id="${oldCustomerId}"]`);
                if (selectedOption) {
                    selectedOption.click();
                }
            }
        });
    </script>
</x-layouts.app>
