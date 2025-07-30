<x-layouts.app title="Import Customers">
    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Import Customers</h2>
                        <a href="{{ route('admin.customers.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                            ← Back to Customers
                        </a>
                    </div>

                    <!-- Instructions -->
                    <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                        <h3 class="font-medium text-blue-900 dark:text-blue-100 mb-2">Import Instructions</h3>
                        <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-1">
                            <li>• CSV file must have columns: <strong>Name, Address, Plan, Date Installed</strong></li>
                            <li>• Date format should be: <strong>M/D/YYYY</strong> (e.g., 7/9/2025 or 11/8/2024)</li>
                            <li>• If a plan doesn't exist, it will be created automatically with default settings</li>
                            <li>• Empty rows and rows without names will be skipped</li>
                            <li>• Customer emails will be generated automatically</li>
                            <li>• Payment notices will be generated based on installation dates</li>
                        </ul>
                    </div>

                    <!-- Sample File Download -->
                    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg">
                        <h3 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Sample CSV Format</h3>
                        <div class="bg-white dark:bg-gray-800 p-3 border rounded font-mono text-sm text-gray-800 dark:text-gray-200 overflow-x-auto">
                            <div>Name,Address,Plan,Date Installed</div>
                            <div>AGSIRAB PUNTA,AGSIRAB,HP 5mbps,7/9/2025</div>
                            <div>MARY GRACE AGUILARIO,AGSIRAB,HP,11/8/2024</div>
                            <div>Lorie Eslaban,Brgy.Ilas sur,HP 5mbps,10/8/2022</div>
                            <div>Kula Amahit,,HP 15mbps,5/24/2023</div>
                        </div>
                        <div class="mt-3">
                            <button type="button" onclick="downloadSampleCsv()" 
                                    class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 underline">
                                Download Sample CSV File
                            </button>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                            <div class="flex">
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                        There were some errors with your submission:
                                    </h3>
                                    <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                        <ul class="list-disc pl-5 space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Import Form -->
                    <form action="{{ route('admin.customers.import') }}" method="POST" enctype="multipart/form-data" 
                          class="space-y-6">
                        @csrf

                        <div>
                            <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Select CSV File
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md hover:border-gray-400 dark:hover:border-gray-500 transition-colors"
                                 ondrop="dropHandler(event);" 
                                 ondragover="dragOverHandler(event);" 
                                 ondragenter="dragEnterHandler(event);" 
                                 ondragleave="dragLeaveHandler(event);">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                        <label for="file" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Upload a file</span>
                                            <input id="file" name="file" type="file" accept=".csv,.txt" class="sr-only" onchange="fileSelected(this)" required>
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">CSV, TXT up to 2MB</p>
                                    <div id="file-info" class="text-sm text-gray-600 dark:text-gray-400 hidden">
                                        <strong>Selected:</strong> <span id="file-name"></span>
                                    </div>
                                </div>
                            </div>
                            @error('file')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('admin.customers.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 font-bold py-2 px-4 rounded transition-colors">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors">
                                Import Customers
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function downloadSampleCsv() {
            const csvContent = `Name,Address,Plan,Date Installed
AGSIRAB PUNTA,AGSIRAB,HP 5mbps,7/9/2025
MARY GRACE AGUILARIO,AGSIRAB,HP,11/8/2024
Lorie Eslaban,Brgy.Ilas sur,HP 5mbps,10/8/2022
Ging2 Carillo,Centro,HP 5mbps,10/22/2022
Kula Amahit,,HP 15mbps,5/24/2023
AMAHIT ELEMENTARY SCHOOL,AMAHIT,HP 15mbps,9/14/2023
Mia Leones,Sigma,HP 5mbps,7/24/2020
"Milaluna Aguro","Dao, Capiz",HP 5mbps,8/9/2020`;
            
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'sample_customers.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        }

        function fileSelected(input) {
            if (input.files && input.files[0]) {
                document.getElementById('file-name').textContent = input.files[0].name;
                document.getElementById('file-info').classList.remove('hidden');
            }
        }

        function dragOverHandler(ev) {
            ev.preventDefault();
            ev.currentTarget.classList.add('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/20');
        }

        function dragEnterHandler(ev) {
            ev.preventDefault();
        }

        function dragLeaveHandler(ev) {
            ev.currentTarget.classList.remove('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/20');
        }

        function dropHandler(ev) {
            ev.preventDefault();
            ev.currentTarget.classList.remove('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/20');

            if (ev.dataTransfer.items) {
                for (let i = 0; i < ev.dataTransfer.items.length; i++) {
                    if (ev.dataTransfer.items[i].kind === 'file') {
                        const file = ev.dataTransfer.items[i].getAsFile();
                        document.getElementById('file').files = ev.dataTransfer.files;
                        fileSelected({ files: [file] });
                        break;
                    }
                }
            }
        }
    </script>
</x-layouts.app>
