<x-layouts.app title="Create Customer">
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Create New Customer</h1>
                <p class="text-gray-600 dark:text-gray-400">Add a new customer to the system</p>
            </div>
            <a href="{{ route('admin.customers.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Customers
            </a>
        </div>

        {{-- Customer Form --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
            <form method="POST" action="{{ route('admin.customers.store') }}" class="space-y-6">
                @csrf

                {{-- Name Fields --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="first_name" 
                               name="first_name" 
                               value="{{ old('first_name') }}"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('first_name') border-red-500 @enderror"
                               required>
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="last_name" 
                               name="last_name" 
                               value="{{ old('last_name') }}"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('last_name') border-red-500 @enderror"
                               required>
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Contact Information --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Email Address
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Phone Number
                        </label>
                        <input type="tel" 
                               id="phone_number" 
                               name="phone_number" 
                               value="{{ old('phone_number') }}"
                               placeholder="+1-555-123-4567"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone_number') border-red-500 @enderror">
                        @error('phone_number')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Service Address --}}
                <div>
                    <label for="service_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Service Address <span class="text-red-500">*</span>
                    </label>
                    <textarea id="service_address" 
                              name="service_address" 
                              rows="3"
                              placeholder="Enter the complete service address where work will be performed"
                              class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('service_address') border-red-500 @enderror"
                              required>{{ old('service_address') }}</textarea>
                    @error('service_address')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Include street address, city, state, and ZIP code
                    </p>
                </div>

                {{-- Plan Information --}}
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Service Plan</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Plan Selection --}}
                        <div>
                            <label for="plan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Service Plan
                            </label>
                            <select id="plan_id" 
                                    name="plan_id" 
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('plan_id') border-red-500 @enderror">
                                <option value="">No plan selected</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" 
                                            data-monthly-rate="{{ $plan->formatted_monthly_rate }}"
                                            data-speed="{{ $plan->speed_mbps }}"
                                            data-type="{{ $plan->getTypeLabel() }}"
                                            {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }} - {{ $plan->formatted_monthly_rate }}/month
                                    </option>
                                @endforeach
                            </select>
                            @error('plan_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Optional: You can assign a plan now or later
                            </p>
                        </div>

                        {{-- Installation Date --}}
                        <div id="plan-installed-field" style="display: none;">
                            <label for="plan_installed_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Installation Date
                            </label>
                            <input type="date" 
                                   id="plan_installed_at" 
                                   name="plan_installed_at" 
                                   value="{{ old('plan_installed_at') }}"
                                   max="{{ date('Y-m-d') }}"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('plan_installed_at') border-red-500 @enderror">
                            @error('plan_installed_at')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Plan Status --}}
                        <div id="plan-status-field" style="display: none;">
                            <label for="plan_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Plan Status
                            </label>
                            <select id="plan_status" 
                                    name="plan_status" 
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('plan_status') border-red-500 @enderror">
                                @foreach(\App\Models\Customer::PLAN_STATUSES as $key => $label)
                                    <option value="{{ $key }}" {{ old('plan_status', 'active') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('plan_status')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Plan Details Display --}}
                    <div id="plan-details" class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-md" style="display: none;">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">Plan Details</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Type:</span>
                                <span id="plan-type" class="ml-1 font-medium text-gray-900 dark:text-gray-100"></span>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Monthly Rate:</span>
                                <span id="plan-rate" class="ml-1 font-medium text-gray-900 dark:text-gray-100"></span>
                            </div>
                            <div id="plan-speed-display" style="display: none;">
                                <span class="text-gray-600 dark:text-gray-400">Speed:</span>
                                <span id="plan-speed" class="ml-1 font-medium text-gray-900 dark:text-gray-100"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.customers.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Create Customer
                    </button>
                </div>
            </form>
        </div>

        {{-- Help Section --}}
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Tips for adding customers</h3>
                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Email addresses are optional but must be unique if provided</li>
                            <li>Phone numbers are optional but recommended for better communication</li>
                            <li>Be specific with service addresses to help technicians locate the site</li>
                            <li>Double-check all information before saving - this will be used for job orders</li>
                            <li>You can assign a service plan now or add it later when editing the customer</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript for plan selection --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const planSelect = document.getElementById('plan_id');
            const planInstalledField = document.getElementById('plan-installed-field');
            const planStatusField = document.getElementById('plan-status-field');
            const planDetails = document.getElementById('plan-details');
            const planType = document.getElementById('plan-type');
            const planRate = document.getElementById('plan-rate');
            const planSpeed = document.getElementById('plan-speed');
            const planSpeedDisplay = document.getElementById('plan-speed-display');
            
            function togglePlanFields() {
                const selectedOption = planSelect.options[planSelect.selectedIndex];
                
                if (planSelect.value) {
                    // Show plan fields
                    planInstalledField.style.display = 'block';
                    planStatusField.style.display = 'block';
                    planDetails.style.display = 'block';
                    
                    // Update plan details
                    planType.textContent = selectedOption.dataset.type || '';
                    planRate.textContent = selectedOption.dataset.monthlyRate || '';
                    
                    if (selectedOption.dataset.speed) {
                        planSpeed.textContent = selectedOption.dataset.speed + ' Mbps';
                        planSpeedDisplay.style.display = 'block';
                    } else {
                        planSpeedDisplay.style.display = 'none';
                    }
                } else {
                    // Hide plan fields
                    planInstalledField.style.display = 'none';
                    planStatusField.style.display = 'none';
                    planDetails.style.display = 'none';
                }
            }
            
            // Initialize on page load
            togglePlanFields();
            
            // Toggle when plan changes
            planSelect.addEventListener('change', togglePlanFields);
        });
    </script>
</x-layouts.app>
