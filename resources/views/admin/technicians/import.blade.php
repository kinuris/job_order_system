<x-layouts.app title="Import Technicians">
    <div class="max-w-4xl mx-auto space-y-6 px-2 sm:px-0">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Import Technicians</h1>
                <p class="text-gray-600 dark:text-gray-400">Upload a CSV file to import multiple technicians</p>
            </div>
            <a href="{{ route('admin.technicians.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Technicians
            </a>
        </div>

        {{-- Instructions Card --}}
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-lg font-medium text-blue-900 dark:text-blue-100">CSV Format Requirements</h3>
                    <div class="mt-2 text-sm text-blue-800 dark:text-blue-200">
                        <p class="mb-2">Your CSV file should have the following columns in this exact order:</p>
                        <div class="bg-white dark:bg-gray-800 rounded-md p-3 font-mono text-xs border">
                            <div class="text-gray-600 dark:text-gray-400">Header row:</div>
                            <div class="font-semibold">Name,Username,Phone Number,Password</div>
                        </div>
                        <ul class="mt-3 space-y-1">
                            <li><strong>Name:</strong> Full name of the technician (required)</li>
                            <li><strong>Username:</strong> Unique username for login (required)</li>
                            <li><strong>Phone Number:</strong> Contact phone number (optional)</li>
                            <li><strong>Password:</strong> Login password (optional - defaults to 'password123')</li>
                        </ul>
                        <div class="mt-3 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-md">
                            <p class="text-yellow-800 dark:text-yellow-200 text-xs">
                                <strong>Note:</strong> If username already exists, that row will be skipped.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sample Download --}}
        <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Sample CSV Template</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Download a sample CSV file to see the correct format:</p>
            <button id="downloadSample" 
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Download Sample CSV
            </button>
        </div>

        {{-- Upload Form --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
            <form action="{{ route('admin.technicians.import') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf

                {{-- File Upload --}}
                <div>
                    <label for="csv_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        CSV File <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                <label for="csv_file" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Upload a file</span>
                                    <input id="csv_file" name="csv_file" type="file" accept=".csv,.txt" required class="sr-only">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                CSV files up to 2MB
                            </p>
                        </div>
                    </div>
                    @error('csv_file')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.technicians.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                        </svg>
                        Import Technicians
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Handle file selection display
        document.getElementById('csv_file').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const fileName = file.name;
                const uploadArea = e.target.closest('.border-dashed');
                const text = uploadArea.querySelector('.text-center');
                text.innerHTML = `
                    <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        <span class="font-medium text-green-600 dark:text-green-400">${fileName}</span> selected
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Click to change file
                    </p>
                `;
            }
        });

        // Generate and download sample CSV
        document.getElementById('downloadSample').addEventListener('click', function() {
            const csvContent = `Name,Username,Phone Number,Password
John Doe,john.doe,+1-555-0123,secure123
Jane Smith,jane.smith,+1-555-0124,password456
Mike Johnson,mike.johnson,,defaultpass
Sarah Wilson,sarah.wilson,+1-555-0126,`;

            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'technicians_sample.csv';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
        });
    </script>
</x-layouts.app>
