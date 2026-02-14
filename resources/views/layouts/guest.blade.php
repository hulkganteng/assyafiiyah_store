<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Assyafiiyah Store') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-pattern min-h-screen flex items-center justify-center relative">
        <!-- Background Overlay for Mood -->
        <div class="absolute inset-0 bg-emerald-900/5 pointer-events-none"></div>

        <div class="w-full sm:max-w-md px-6 py-4 relative z-10 flex flex-col items-center">
             <!-- Logo Section -->
            <div class="mb-8 flex flex-col items-center">
                <a href="/" class="flex flex-col items-center gap-3 group">
                    <img src="{{ asset('Logo.png') }}" class="w-24 h-24 object-contain drop-shadow-lg transform group-hover:scale-105 transition-transform duration-300" alt="Assyafiiyah Store">
                    <span class="text-2xl font-serif font-bold text-emerald-900 tracking-tight">Assyafiiyah Store</span>
                </a>
            </div>

            <!-- Card -->
            <div class="w-full bg-white/80 backdrop-blur-xl shadow-2xl border border-white/50 rounded-3xl overflow-hidden p-8 sm:p-10 transform transition-all hover:scale-[1.01]">
                {{ $slot }}
            </div>
            
            <div class="mt-8 text-center text-sm text-emerald-800/60 font-medium">
                &copy; {{ date('Y') }} Assyafiiyah Store. Berkah & Amanah.
            </div>
        </div>
    </body>
</html>
