<nav x-data="{ open: false }" class="bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <img src="{{ asset('Logo.png') }}" class="w-10 h-10 object-contain" alt="Assyafiiyah Store">
                        <span class="font-serif text-xl font-bold text-emerald-900 tracking-tight">Assyafiiyah</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex items-center h-full">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')" class="text-gray-600 hover:text-emerald-600 font-medium">
                        {{ __('Beranda') }}
                    </x-nav-link>
                    
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="text-gray-600 hover:text-emerald-600">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')" class="text-gray-600 hover:text-emerald-600">
                                {{ __('Produk') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')" class="text-gray-600 hover:text-emerald-600">
                                {{ __('Kategori') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.discounts.index')" :active="request()->routeIs('admin.discounts.*')" class="text-gray-600 hover:text-emerald-600">
                                {{ __('Diskon') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')" class="text-gray-600 hover:text-emerald-600">
                                {{ __('Pesanan') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.bank-accounts.index')" :active="request()->routeIs('admin.bank-accounts.*')" class="text-gray-600 hover:text-emerald-600">
                                {{ __('Rekening') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Cart & Settings -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                 <!-- Cart Link with Gold Accent -->
                 <a href="{{ route('cart.index') }}" class="group flex items-center gap-2 text-gray-500 hover:text-emerald-700 px-3 py-2 rounded-md transition-all duration-300">
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        @if(session('cart'))
                            <span class="absolute -top-1.5 -right-1.5 bg-gold-500 text-white rounded-full text-[10px] w-4 h-4 flex items-center justify-center font-bold shadow-sm">
                                {{ count(session('cart')) }}
                            </span>
                        @endif
                    </div>
                    <span class="font-medium text-sm group-hover:text-emerald-700">Keranjang</span>
                </a>

                <div class="h-6 w-px bg-gray-200 mx-4"></div>

                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-full text-emerald-800 bg-emerald-50 hover:bg-emerald-100 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @if(Auth::user()->role === 'admin')
                                <div class="block px-4 py-2 text-xs text-gray-400">Admin Controls</div>
                                <x-dropdown-link :href="route('admin.dashboard')">Dashboard</x-dropdown-link>
                                <div class="border-t border-gray-100"></div>
                            @endif
                            
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                @endauth
            </div>

            <!-- Mobile Cart + Hamburger -->
            <div class="-me-2 flex items-center gap-2 sm:hidden">
                <a href="{{ route('cart.index') }}" class="relative inline-flex items-center justify-center p-2 rounded-md text-emerald-700 hover:text-emerald-900 focus:outline-none transition duration-150 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    @if(session('cart'))
                        <span class="absolute -top-1 -right-1 bg-gold-500 text-white rounded-full text-[10px] w-4 h-4 flex items-center justify-center font-bold shadow-sm">
                            {{ count(session('cart')) }}
                        </span>
                    @endif
                </a>
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-emerald-700 hover:text-emerald-900 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-b border-gray-100">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('Beranda') }}
            </x-responsive-nav-link>
            @auth
                @if(Auth::user()->role === 'admin')
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')">
                        {{ __('Produk') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                        {{ __('Kategori') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.discounts.index')" :active="request()->routeIs('admin.discounts.*')">
                        {{ __('Diskon') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')">
                        {{ __('Pesanan') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.bank-accounts.index')" :active="request()->routeIs('admin.bank-accounts.*')">
                        {{ __('Rekening') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
            @endauth
        </div>
    </div>
</nav>
