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
    <body class="font-sans antialiased text-gray-800 bg-pattern min-h-screen flex flex-col">
        <!-- Banner Top (Optional) -->
        <!-- Banner Top (Optional) - REMOVED per user request -->
        <!-- <div class="bg-emerald-900 text-white text-xs py-2 text-center">
            <p>Gratis Ongkir untuk Pemesanan di Atas Rp 500.000 (Khusus Pesantren)</p>
        </div> -->

        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow border-b border-gray-100">
                <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 text-center">
                   <h1 class="font-serif text-3xl text-emerald-900 font-bold mb-2">{{ $header }}</h1>
                   <div class="h-1 w-20 bg-gold-400 mx-auto rounded"></div>
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="flex-grow">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-emerald-900 text-white py-12 border-t-4 border-gold-500 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="font-serif text-2xl font-bold mb-4 text-gold-400">Assyafiiyah Store</h3>
                    <p class="text-emerald-100 text-sm leading-relaxed">
                        Menyediakan kebutuhan santri dan masyarakat umum dengan produk halal, berkualitas, dan harga terjangkau.
                    </p>
                </div>
                <div>
                    <h4 class="font-bold text-lg mb-4">Link Cepat</h4>
                    <ul class="space-y-2 text-sm text-emerald-100">
                        <li><a href="{{ route('home') }}" class="hover:text-gold-300">Beranda</a></li>
                        <li><a href="{{ route('cart.index') }}" class="hover:text-gold-300">Keranjang</a></li>
                        <li><a href="#" class="hover:text-gold-300">Tentang Kami</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-lg mb-4">Kontak</h4>
                    <p class="text-emerald-100 text-sm">Jl.Nongko Kerep RT II RW I Desa Bungah<br>Kec.Bungah Kab.Gresik<br>( Depan Gang Polsek Bungah )</p>
                    <p class="text-emerald-100 text-sm mt-2">WhatsApp: +62 812 3456 7890</p>
                </div>
            </div>
            <div class="text-center text-emerald-200 text-xs mt-12 pt-8 border-t border-emerald-800">
                &copy; {{ date('Y') }} Assyafiiyah Store. All rights reserved.
            </div>
        </footer>
    </body>
</html>
