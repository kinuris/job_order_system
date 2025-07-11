<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        <title>{{ config('app.name', 'Job Order System') }} - Professional Service Management</title>
    </head>
    <body class="min-h-screen bg-gray-50 dark:bg-gray-900 antialiased"
          x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' || false }"
          x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
          :class="{ 'dark': darkMode }">
        <header class="fixed top-0 left-0 right-0 z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @if (Route::has('login'))
                    <nav class="flex items-center justify-between h-16">
                        <!-- Logo/Brand -->
                        <div class="flex items-center">
                            <x-app-logo-icon class="h-8 w-auto text-gray-800 dark:text-gray-200" />
                            <span class="ml-2 text-xl font-bold text-gray-900 dark:text-white">Job Order System</span>
                        </div>
                        
                        <!-- Navigation Links -->
                        <div class="flex items-center gap-4">
                            @auth
                                <a
                                    href="{{ url('/dashboard') }}"
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
                                >
                                    Dashboard
                                </a>
                            @else
                                <a
                                    href="{{ route('login') }}"
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
                                >
                                    Log in
                                </a>
                            @endauth
                        </div>
                    </nav>
                @endif
            </div>
        </header>
        <div class="relative min-h-screen flex items-center justify-center overflow-hidden pt-16">
            <!-- Enhanced Background -->
            <div class="absolute inset-0 -z-10 h-full w-full bg-white dark:bg-gray-900">
                <div class="absolute inset-0 bg-[linear-gradient(to_right,#f0f0f0_1px,transparent_1px),linear-gradient(to_bottom,#f0f0f0_1px,transparent_1px)] dark:bg-[linear-gradient(to_right,#374151_1px,transparent_1px),linear-gradient(to_bottom,#374151_1px,transparent_1px)] bg-[size:6rem_4rem]"></div>
                <div class="absolute bottom-0 left-0 right-0 top-0 bg-[radial-gradient(circle_800px_at_100%_200px,#ddd6fe,transparent)] dark:bg-[radial-gradient(circle_800px_at_100%_200px,#312e81,transparent)]"></div>
                <div class="absolute top-0 left-0 right-0 bottom-0 bg-[radial-gradient(circle_600px_at_0%_100%,#fecaca,transparent)] dark:bg-[radial-gradient(circle_600px_at_0%_100%,#7f1d1d,transparent)]"></div>
            </div>
            
            <div class="container mx-auto px-4 relative z-10">
                <div class="text-center max-w-5xl mx-auto">
                    <!-- Logo with enhanced styling -->
                    <div class="mb-8">
                        <x-app-logo-icon class="mx-auto h-24 w-auto text-gray-800 dark:text-gray-200 drop-shadow-lg" />
                    </div>
                    
                    <!-- Hero Section -->
                    <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight text-gray-900 dark:text-white mb-6">
                        <span class="block">Job Order</span>
                        <span class="block bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400 bg-clip-text text-transparent">
                            Management
                        </span>
                    </h1>
                    
                    <p class="mt-6 text-xl sm:text-2xl leading-8 text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                        Transform your service operations with our comprehensive job order management platform. 
                        <span class="block mt-2 text-lg text-gray-500 dark:text-gray-400">Efficient • Professional • Reliable</span>
                    </p>

                    <!-- Feature highlights -->
                    <div class="mt-12 grid grid-cols-1 sm:grid-cols-3 gap-6 max-w-4xl mx-auto">
                        <div class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
                            <div class="text-indigo-600 dark:text-indigo-400 text-2xl mb-3">⚡</div>
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Fast Processing</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Quick job order creation and tracking</p>
                        </div>
                        <div class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
                            <div class="text-indigo-600 dark:text-indigo-400 text-2xl mb-3">📊</div>
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Real-time Updates</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Live status tracking and notifications</p>
                        </div>
                        <div class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
                            <div class="text-indigo-600 dark:text-indigo-400 text-2xl mb-3">🔒</div>
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Secure Access</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Role-based permissions and data protection</p>
                        </div>
                    </div>
                    
                    <!-- CTA Section -->
                    <div class="mt-16 flex flex-col sm:flex-row items-center justify-center gap-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="group relative inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                    <span>Go to Dashboard</span>
                                    <svg class="ml-2 -mr-1 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="group mb-8 relative inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                    <span>Log in to Get Started</span>
                                    <svg class="ml-2 -mr-1 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </a>
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>
