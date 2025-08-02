<x-layouts.app title="Edit Customer">
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Edit Customer</h1>
                <p class="text-gray-600 dark:text-gray-400">Update customer information and contact details</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.customers.show', $customer) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    View Customer
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

        {{-- Customer Form --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
            <form method="POST" action="{{ route('admin.customers.update', $customer) }}" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Name Fields --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="first_name" 
                               name="first_name" 
                               value="{{ old('first_name', $customer->first_name) }}"
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
                               value="{{ old('last_name', $customer->last_name) }}"
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
                               value="{{ old('email', $customer->email) }}"
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
                               value="{{ old('phone_number', $customer->phone_number) }}"
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
                              required>{{ old('service_address', $customer->service_address) }}</textarea>
                    @error('service_address')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Include street address, city, state, and ZIP code
                    </p>
                </div>

                {{-- Plan Information --}}
                <div class="space-y-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Plan Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="plan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Service Plan
                            </label>
                            <select id="plan_id" 
                                    name="plan_id"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('plan_id') border-red-500 @enderror">
                                <option value="">No Plan Selected</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" 
                                            {{ old('plan_id', $customer->plan_id) == $plan->id ? 'selected' : '' }}
                                            data-type="{{ $plan->type }}"
                                            data-rate="{{ $plan->formatted_monthly_rate }}">
                                        {{ $plan->name }} - {{ $plan->formatted_monthly_rate }}/month
                                    </option>
                                @endforeach
                            </select>
                            @error('plan_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="plan_installed_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Installation Date
                            </label>
                            <input type="date" 
                                   id="plan_installed_at" 
                                   name="plan_installed_at" 
                                   value="{{ old('plan_installed_at', $customer->plan_installed_at ? $customer->plan_installed_at->format('Y-m-d') : '') }}"
                                   data-original-value="{{ $customer->plan_installed_at ? $customer->plan_installed_at->format('Y-m-d') : '' }}"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('plan_installed_at') border-red-500 @enderror">
                            @error('plan_installed_at')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="plan_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Plan Status
                            </label>
                            <select id="plan_status" 
                                    name="plan_status"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('plan_status') border-red-500 @enderror">
                                <option value="">Select Status</option>
                                @foreach(\App\Models\Customer::PLAN_STATUSES as $key => $label)
                                    <option value="{{ $key }}" {{ old('plan_status', $customer->plan_status) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('plan_status')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div id="plan-details" class="hidden p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-md">
                        <h4 class="font-medium text-blue-900 dark:text-blue-100 mb-2">Plan Details</h4>
                        <div id="plan-info" class="text-sm text-blue-800 dark:text-blue-200"></div>
                    </div>
                </div>

                {{-- Hidden field to track installation date change confirmation --}}
                <input type="hidden" id="installation_date_change_confirmed" name="installation_date_change_confirmed" value="0">

                {{-- Form Actions --}}
                <div class="flex items-center justify-end pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.customers.show', $customer) }}" 
                           class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Cancel
                        </a>
                        <button type="button" 
                                id="update-customer-btn"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Update Customer
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Delete Customer Section (Separate) --}}
        @if($customer->jobOrders->count() === 0)
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-xl p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Danger Zone</h3>
                    <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                        <p>Once you delete this customer, there is no going back. Please be certain.</p>
                    </div>
                    <div class="mt-4">
                        <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}" 
                              onsubmit="return confirm('Are you sure you want to delete this customer? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete Customer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Customer Summary --}}
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Customer Summary</h3>
                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Customer since: {{ $customer->created_at->format('F d, Y') }}</li>
                            <li>Total job orders: {{ $customer->jobOrders->count() }}</li>
                            <li>Completed jobs: {{ $customer->jobOrders->where('status', 'completed')->count() }}</li>
                            <li>Last updated: {{ $customer->updated_at->format('F d, Y \a\t g:i A') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Installation Date Change Warning Modal --}}
    <div id="installation-date-warning-modal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm overflow-y-auto h-full w-full z-50 hidden opacity-0 transition-opacity duration-300" style="z-index: 9999;">
        <div class="flex items-center justify-center min-h-full p-4">
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 w-full max-w-md transform transition-all duration-300 scale-95 opacity-0" id="modal-content">
                {{-- Modal Header --}}
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-r from-red-500 to-red-600 flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                Confirm Installation Date Change
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                This action will affect payment records
                            </p>
                        </div>
                    </div>
                </div>
                
                {{-- Modal Body --}}
                <div class="px-6 py-6">
                    <div class="space-y-4">
                        {{-- Warning Message --}}
                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-red-800 dark:text-red-300">
                                        Payment Records Will Be Deleted
                                    </h4>
                                    <div class="mt-2 text-sm text-red-700 dark:text-red-400">
                                        <p>
                                            Changing the installation date will permanently delete 
                                            <strong><span id="modal-payment-count">{{ $customer->payments()->confirmed()->count() }}</span> payment record(s)</strong> 
                                            for this customer.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Impact Details --}}
                        <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-amber-800 dark:text-amber-300">
                                        Required Actions
                                    </h4>
                                    <div class="mt-2 text-sm text-amber-700 dark:text-amber-400">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Payment history will be permanently removed</li>
                                            <li>Payment notices will be regenerated</li>
                                            <li>Manual re-entry of payments will be required</li>
                                            <li>This action cannot be undone</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Modal Footer --}}
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 rounded-b-2xl border-t border-gray-200 dark:border-gray-700">
                    <div class="flex justify-end space-x-3">
                        <button id="modal-cancel-btn" 
                                type="button"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancel Changes
                        </button>
                        <button id="modal-confirm-btn" 
                                type="button"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 transform hover:scale-105">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete Payments & Continue
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript for plan selection --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const planSelect = document.getElementById('plan_id');
            const planDetails = document.getElementById('plan-details');
            const planInfo = document.getElementById('plan-info');
            const planStatusSelect = document.getElementById('plan_status');
            const planInstalledAt = document.getElementById('plan_installed_at');
            
            // Modal elements
            const updateBtn = document.getElementById('update-customer-btn');
            const modal = document.getElementById('installation-date-warning-modal');
            const modalCancelBtn = document.getElementById('modal-cancel-btn');
            const modalConfirmBtn = document.getElementById('modal-confirm-btn');
            const confirmationInput = document.getElementById('installation_date_change_confirmed');
            const customerForm = updateBtn ? updateBtn.closest('form') : null;
            
            // Ensure all required elements exist
            if (!updateBtn || !modal || !modalCancelBtn || !modalConfirmBtn || !confirmationInput || !planInstalledAt) {
                console.error('Required elements not found for modal functionality');
                return;
            }
            
            // Installation date tracking
            const originalInstallationDate = planInstalledAt.dataset.originalValue || '';
            const hasPayments = {{ $customer->payments()->confirmed()->count() > 0 ? 'true' : 'false' }};

            function updatePlanDetails() {
                const selectedOption = planSelect.options[planSelect.selectedIndex];
                
                if (selectedOption.value) {
                    const planType = selectedOption.dataset.type;
                    const planRate = selectedOption.dataset.rate;
                    
                    planInfo.innerHTML = `
                        <p><strong>Type:</strong> ${planType.charAt(0).toUpperCase() + planType.slice(1)}</p>
                        <p><strong>Monthly Rate:</strong> ${planRate}</p>
                    `;
                    planDetails.classList.remove('hidden');
                    
                    // Auto-set plan status to active if not already set
                    if (!planStatusSelect.value) {
                        planStatusSelect.value = 'active';
                    }
                    
                    // Auto-set installation date to today if not already set
                    if (!planInstalledAt.value) {
                        planInstalledAt.value = new Date().toISOString().split('T')[0];
                    }
                } else {
                    planDetails.classList.add('hidden');
                    planStatusSelect.value = '';
                    planInstalledAt.value = '';
                }
            }

            function checkInstallationDateChange() {
                const newDate = planInstalledAt.value;
                return hasPayments && originalInstallationDate && newDate !== originalInstallationDate;
            }

            function showModal() {
                const modalContent = document.getElementById('modal-content');
                modal.classList.remove('hidden');
                
                // Force reflow to ensure the hidden class is removed before animation
                modal.offsetHeight;
                
                // Animate in
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    modalContent.classList.remove('scale-95', 'opacity-0');
                    modalContent.classList.add('scale-100', 'opacity-100');
                }, 10);
            }

            function hideModal() {
                const modalContent = document.getElementById('modal-content');
                
                // Animate out
                modal.classList.add('opacity-0');
                modalContent.classList.remove('scale-100', 'opacity-100');
                modalContent.classList.add('scale-95', 'opacity-0');
                
                // Hide modal after animation
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }

            // Handle update button click
            updateBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Check if we need to show the modal (customer has payments AND installation date changed)
                if (hasPayments && checkInstallationDateChange()) {
                    showModal();
                } else {
                    // No modal needed - either no payments or no installation date change
                    confirmationInput.value = '0';
                    customerForm.submit();
                }
            });

            // Handle modal cancel
            modalCancelBtn.addEventListener('click', function() {
                hideModal();
            });

            // Handle modal confirm
            modalConfirmBtn.addEventListener('click', function() {
                confirmationInput.value = '1';
                hideModal();
                customerForm.submit();
            });

            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    hideModal();
                }
            });

            // Handle ESC key to close modal
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    hideModal();
                }
            });

            planSelect.addEventListener('change', updatePlanDetails);
            
            // Initialize on page load
            updatePlanDetails();
        });
    </script>
</x-layouts.app>
