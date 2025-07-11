<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-900 antialiased">
        <div class="flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-md flex-col gap-6">
                <!-- Logo and Branding -->
                <div class="flex flex-col items-center gap-4 text-center">
                    <a href="{{ route('home') }}" class="flex flex-col items-center gap-3 font-medium" wire:navigate>
                        <div class="flex h-16 w-16 items-center justify-center rounded-xl bg-white dark:bg-zinc-800 shadow-lg ring-1 ring-zinc-900/5 dark:ring-zinc-100/10">
                            <x-app-logo-icon class="size-10 fill-current text-zinc-900 dark:text-zinc-100" />
                        </div>
                        <div class="space-y-1">
                            <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">{{ config('app.name', 'Job Order System') }}</h1>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">Professional Service Management</p>
                        </div>
                    </a>
                </div>
                
                <!-- Login Form Container -->
                <div class="rounded-xl bg-white dark:bg-zinc-800 p-6 shadow-lg ring-1 ring-zinc-900/5 dark:ring-zinc-100/10">
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
