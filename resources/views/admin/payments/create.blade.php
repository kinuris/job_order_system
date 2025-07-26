<x-layouts.app title="Record Payment">
    <div class="space-y-4 sm:space-y-6 px-2 sm:px-0">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">Record Payment</h1>
                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400">Add a new payment record for a customer</p>
            </div>
            <a href="{{ route('admin.payments.index') }}" 
               class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Payments
            </a>
        </div>

        {{-- Payment Form --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
            <form method="POST" action="{{ route('admin.payments.store') }}" class="space-y-6">
                @csrf

                {{-- Customer Selection --}}
                <div>
                    <label for="customer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Customer <span class="text-red-500">*</span>
                    </label>
                    <select name="customer_id" id="customer_id" required 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('customer_id') border-red-500 @enderror">
                        <option value="">Select a customer</option>
                        @foreach($customers as $cust)
                            <option value="{{ $cust->id }}" 
                                    data-plan="{{ $cust->plan->name ?? 'No Plan' }}"
                                    data-rate="{{ $cust->plan->monthly_rate ?? 0 }}"
                                    data-installation-date="{{ $cust->plan_installed_at ? $cust->plan_installed_at->format('Y-m-d') : '' }}"
                                    {{ (old('customer_id') == $cust->id || ($customer && $customer->id == $cust->id)) ? 'selected' : '' }}>
                                {{ $cust->full_name }} - {{ $cust->plan->name ?? 'No Plan' }} (₱{{ number_format($cust->plan->monthly_rate ?? 0, 2) }})
                                @if($cust->plan_installed_at)
                                    - Installed {{ $cust->plan_installed_at->format('M Y') }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Customer Info Display --}}
                <div id="customer-info" class="hidden bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-2">Customer Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-blue-700 dark:text-blue-300">Plan:</span>
                            <span id="display-plan" class="text-blue-900 dark:text-blue-100 font-medium ml-1"></span>
                        </div>
                        <div>
                            <span class="text-blue-700 dark:text-blue-300">Monthly Rate:</span>
                            <span id="display-rate" class="text-blue-900 dark:text-blue-100 font-medium ml-1"></span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Payment Amount --}}
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Payment Amount <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400 sm:text-sm">₱</span>
                            </div>
                            <input type="number" name="amount" id="amount" step="0.01" min="0.01" required
                                   value="{{ old('amount') }}"
                                   class="block w-full pl-7 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('amount') border-red-500 @enderror"
                                   placeholder="0.00">
                        </div>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Payment Date --}}
                    <div>
                        <label for="payment_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Payment Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="payment_date" id="payment_date" required
                               value="{{ old('payment_date', now()->format('Y-m-d')) }}"
                               max="{{ now()->format('Y-m-d') }}"
                               class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('payment_date') border-red-500 @enderror">
                        @error('payment_date')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Months Covered Selection --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        Months Covered <span class="text-red-500">*</span>
                    </label>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                            Select up to which month this payment should cover. The system will calculate the total months and amount.
                        </p>
                        <div id="months-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2">
                            <!-- Months will be populated by JavaScript -->
                        </div>
                        <div class="mt-3 text-sm text-gray-600 dark:text-gray-400">
                            <span id="months-summary">No months selected</span>
                        </div>
                    </div>
                    <input type="hidden" name="months_covered" id="months_covered" value="{{ old('months_covered', 1) }}" required>
                    @error('months_covered')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Payment Date --}}
                    <div>
                        <label for="payment_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Payment Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="payment_date" id="payment_date" required
                               value="{{ old('payment_date', now()->format('Y-m-d')) }}"
                               max="{{ now()->format('Y-m-d') }}"
                               class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('payment_date') border-red-500 @enderror">
                        @error('payment_date')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Payment Method --}}
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Payment Method <span class="text-red-500">*</span>
                        </label>
                        <select name="payment_method" id="payment_method" required
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('payment_method') border-red-500 @enderror">
                            <option value="">Select payment method</option>
                            @foreach(\App\Models\CustomerPayment::PAYMENT_METHODS as $key => $label)
                                <option value="{{ $key }}" {{ old('payment_method') === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Reference Number --}}
                <div>
                    <label for="reference_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Reference Number
                    </label>
                    <input type="text" name="reference_number" id="reference_number" maxlength="255"
                           value="{{ old('reference_number') }}"
                           class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('reference_number') border-red-500 @enderror"
                           placeholder="For digital payments, bank transfers, etc.">
                    @error('reference_number')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Notes --}}
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Notes
                    </label>
                    <textarea name="notes" id="notes" rows="3" maxlength="1000"
                              class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-500 @enderror"
                              placeholder="Additional notes about this payment...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit Buttons --}}
                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                    <button type="submit" 
                            class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Record Payment
                    </button>
                    <a href="{{ route('admin.payments.index') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-lg font-semibold text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-400 dark:hover:bg-gray-500 focus:bg-gray-400 dark:focus:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- JavaScript for dynamic customer info and month selection --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const customerSelect = document.getElementById('customer_id');
            const customerInfo = document.getElementById('customer-info');
            const displayPlan = document.getElementById('display-plan');
            const displayRate = document.getElementById('display-rate');
            const amountInput = document.getElementById('amount');
            const monthsInput = document.getElementById('months_covered');
            const monthsGrid = document.getElementById('months-grid');
            const monthsSummary = document.getElementById('months-summary');

            let selectedMonthIndex = 0; // 0 = installation month
            let currentMonthlyRate = 0;
            let installationDate = null;

            // Generate month options starting from installation date
            function generateMonths() {
                if (!installationDate) {
                    return [];
                }
                
                const months = [];
                const startDate = new Date(installationDate);
                const today = new Date();
                
                // Calculate months from installation date to current month + 1 (for next month)
                let currentDate = new Date(startDate.getFullYear(), startDate.getMonth(), 1);
                let monthIndex = 0;
                
                // Generate months from installation until current month + a few future months for advance payments
                const maxDate = new Date(today.getFullYear(), today.getMonth() + 3, 1); // Current month + 3 months ahead
                
                while (currentDate <= maxDate) {
                    const monthData = {
                        index: monthIndex,
                        date: new Date(currentDate),
                        label: currentDate.toLocaleDateString('en-US', { month: 'short', year: 'numeric' }),
                        fullLabel: currentDate.toLocaleDateString('en-US', { month: 'long', year: 'numeric' }),
                        isPast: currentDate < today,
                        isCurrent: currentDate.getMonth() === today.getMonth() && currentDate.getFullYear() === today.getFullYear()
                    };
                    
                    months.push(monthData);
                    
                    // Move to next month
                    currentDate.setMonth(currentDate.getMonth() + 1);
                    monthIndex++;
                }
                
                return months;
            }

            function renderMonths() {
                const months = generateMonths();
                
                if (months.length === 0) {
                    monthsGrid.innerHTML = '<p class="text-sm text-gray-500 dark:text-gray-400 col-span-full">Please select a customer with an installation date.</p>';
                    return;
                }
                
                monthsGrid.innerHTML = '';
                
                months.forEach(month => {
                    const button = document.createElement('button');
                    button.type = 'button';
                    
                    const isSelected = month.index <= selectedMonthIndex;
                    const isPastDue = month.isPast && !isSelected;
                    
                    let buttonClass = 'px-3 py-2 text-sm font-medium rounded-lg border transition-colors ';
                    
                    if (isSelected) {
                        buttonClass += 'bg-blue-600 border-blue-600 text-white';
                    } else if (isPastDue) {
                        buttonClass += 'bg-red-100 border-red-300 text-red-700 dark:bg-red-900/20 dark:border-red-800 dark:text-red-400';
                    } else {
                        buttonClass += 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700';
                    }
                    
                    button.className = buttonClass;
                    button.textContent = month.label;
                    
                    let title = month.fullLabel;
                    if (month.isPast && !isSelected) {
                        title += ' (Past due)';
                    } else if (month.isCurrent) {
                        title += ' (Current month)';
                    }
                    button.title = title;
                    
                    button.addEventListener('click', function() {
                        selectedMonthIndex = month.index;
                        updateMonthSelection();
                    });
                    
                    monthsGrid.appendChild(button);
                });
            }

            function updateMonthSelection() {
                const monthsCovered = selectedMonthIndex + 1;
                monthsInput.value = monthsCovered;
                
                // Update visual selection
                renderMonths();
                
                // Update summary
                const months = generateMonths();
                if (months.length > 0) {
                    const startMonth = months[0].fullLabel;
                    const endMonth = months[selectedMonthIndex].fullLabel;
                    
                    if (monthsCovered === 1) {
                        monthsSummary.textContent = `Covering ${startMonth} (1 month)`;
                    } else {
                        monthsSummary.textContent = `Covering ${startMonth} to ${endMonth} (${monthsCovered} months)`;
                    }
                } else {
                    monthsSummary.textContent = 'No installation date available';
                }
                
                // Update amount
                updateAmount();
            }

            function updateCustomerInfo() {
                const selectedOption = customerSelect.options[customerSelect.selectedIndex];
                
                if (selectedOption.value) {
                    const plan = selectedOption.getAttribute('data-plan');
                    const rate = parseFloat(selectedOption.getAttribute('data-rate'));
                    const installationDateStr = selectedOption.getAttribute('data-installation-date');
                    
                    displayPlan.textContent = plan;
                    displayRate.textContent = '₱' + rate.toFixed(2);
                    customerInfo.classList.remove('hidden');
                    
                    currentMonthlyRate = rate;
                    
                    if (installationDateStr) {
                        installationDate = new Date(installationDateStr);
                        selectedMonthIndex = 0; // Reset to first month
                        renderMonths();
                        updateMonthSelection();
                    } else {
                        installationDate = null;
                        monthsGrid.innerHTML = '<p class="text-sm text-red-500 dark:text-red-400 col-span-full">This customer has no installation date set.</p>';
                        monthsSummary.textContent = 'Installation date required';
                    }
                } else {
                    customerInfo.classList.add('hidden');
                    currentMonthlyRate = 0;
                    installationDate = null;
                    monthsGrid.innerHTML = '';
                    monthsSummary.textContent = 'No customer selected';
                }
            }

            function updateAmount() {
                if (currentMonthlyRate > 0) {
                    const monthsCovered = parseInt(monthsInput.value) || 1;
                    const suggestedAmount = currentMonthlyRate * monthsCovered;
                    
                    if (!amountInput.value || amountInput.value == 0) {
                        amountInput.value = suggestedAmount.toFixed(2);
                    }
                }
            }

            // Event listeners
            customerSelect.addEventListener('change', updateCustomerInfo);

            // Initialize
            updateCustomerInfo();
            
            // Update amount when user manually changes it
            amountInput.addEventListener('input', function() {
                // Don't auto-update if user is manually entering amount
            });
        });
    </script>
</x-layouts.app>
