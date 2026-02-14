<x-app-layout>
    <!-- Hero Section -->
    <div class="relative bg-emerald-900 border-b-8 border-gold-500 overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-islamic-pattern"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-10 flex flex-col md:flex-row items-center justify-between">
            <div class="md:w-1/2 text-center md:text-left mb-10 md:mb-0">
                <span class="inline-block py-1 px-3 rounded-full bg-gold-400 text-emerald-950 text-xs font-bold tracking-wider mb-4 uppercase">Selamat Datang di Assyafiiyah Store</span>
                <h1 class="text-4xl md:text-6xl font-serif font-bold text-white mb-6 leading-tight">
                    Belanja Kebutuhan <br><span class="text-gold-400">Orisinil & Terpercaya</span>
                </h1>
                <p class="text-emerald-100 text-lg mb-8 max-w-lg mx-auto md:mx-0">
                    Menyediakan berbagai produk berkualitas untuk santri dan Masyarakat umum. Dijamin terpercaya dan amanah.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                    <a href="#products" class="px-8 py-3 bg-gold-500 text-white font-bold rounded-full shadow-lg hover:bg-gold-600 transition transform hover:-translate-y-1">Mulai Belanja</a>
                    <a href="#" class="px-8 py-3 border border-emerald-400 text-emerald-100 font-bold rounded-full hover:bg-emerald-800 transition">Tentang Kami</a>
                </div>
            </div>
            <!-- Decorative / Image placeholder -->
            <div class="md:w-1/3 flex justify-center">
                <div class="relative w-64 h-64 md:w-80 md:h-80 bg-gradient-to-tr from-gold-300 to-gold-500 rounded-full p-2 shadow-2xl animate-pulse-slow">
                     <div class="w-full h-full bg-white rounded-full overflow-hidden relative flex items-center justify-center border-4 border-white/20">
                        <img src="{{ asset('Logo.png') }}" class="w-4/5 h-4/5 object-contain" alt="Logo Assyafiiyah">
                     </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div id="products" class="py-16 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Filter & Search Bar -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
                <!-- Categories -->
                <div class="flex gap-2 overflow-x-auto pb-2 w-full md:w-auto items-center no-scrollbar">
                    <a href="{{ route('home') }}" class="px-5 py-2 rounded-full text-sm font-medium transition-all {{ !request('category') ? 'bg-emerald-700 text-white shadow-md' : 'bg-white text-gray-600 hover:bg-emerald-50 border border-gray-200' }}">
                        Semua
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('home', array_merge(request()->all(), ['category' => $cat->slug])) }}" class="whitespace-nowrap px-5 py-2 rounded-full text-sm font-medium transition-all {{ request('category') == $cat->slug ? 'bg-emerald-700 text-white shadow-md' : 'bg-white text-gray-600 hover:bg-emerald-50 border border-gray-200' }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>

                <!-- Search -->
                <form method="GET" action="{{ route('home') }}" class="relative w-full md:w-96">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk impian..." class="w-full pl-10 pr-4 py-2.5 rounded-full border-gray-200 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 text-sm">
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </form>
            </div>

            <!-- Products Grid -->
             @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach($products as $product)
                        <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden flex flex-col h-full relative">
                            <!-- Badge Stock -->
                            <!-- Badge Stock -->
                             @if($product->is_preorder)
                                <span class="absolute top-3 left-3 bg-blue-600 text-white text-[10px] font-bold px-2 py-1 rounded-sm shadow z-10">Pre Order</span>
                             @elseif($product->stock < 5 && $product->stock > 0)
                                <span class="absolute top-3 left-3 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded-sm shadow z-10">Tersisa {{ $product->stock }}</span>
                            @elseif($product->stock == 0)
                                <span class="absolute top-3 left-3 bg-gray-600 text-white text-[10px] font-bold px-2 py-1 rounded-sm shadow z-10">Habis</span>
                            @endif
                            @if($product->has_active_discount)
                                <span class="absolute top-3 right-3 bg-rose-600 text-white text-[10px] font-bold px-2 py-1 rounded-sm shadow z-10">{{ $product->discount_label }}</span>
                            @endif

                            <!-- Image -->
                            <div class="relative h-60 overflow-hidden bg-gray-50">
                                <img src="{{ $product->images->first() ? asset('storage/' . $product->images->first()->path) : 'https://via.placeholder.com/300' }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <!-- Quick Action Overlay -->
                                <div class="absolute inset-x-0 bottom-0 p-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300 flex justify-center">
                                    <form action="{{ route('cart.add') }}" method="POST" class="w-full">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="w-full bg-white/90 backdrop-blur text-emerald-800 font-bold py-2 rounded-lg shadow-lg hover:bg-emerald-600 hover:text-white transition-colors" {{ ($product->stock < 1 && !$product->is_preorder) ? 'disabled' : '' }}>
                                            + Keranjang
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-5 flex flex-col flex-grow">
                                <div class="text-xs text-gold-600 font-bold uppercase tracking-wide mb-1">{{ $product->category->name }}</div>
                                <h3 class="text-lg font-serif font-bold text-gray-900 mb-2 leading-tight">
                                    <a href="{{ route('products.show', $product->slug) }}" class="hover:text-emerald-700 transition">
                                        {{ $product->name }}
                                    </a>
                                </h3>
                                <div class="mt-auto flex items-center justify-between border-t border-gray-50 pt-4">
                                     @if($product->has_active_discount)
                                        <div class="flex flex-col">
                                            <span class="text-xs text-gray-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                            <span class="text-lg font-bold text-emerald-700">Rp {{ number_format($product->discounted_price, 0, ',', '.') }}</span>
                                        </div>
                                     @else
                                        <span class="text-lg font-bold text-emerald-700">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                     @endif
                                     <a href="{{ route('products.show', $product->slug) }}" class="text-gray-400 hover:text-emerald-600 transition">
                                         <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                     </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-12 flex justify-center">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-20 bg-white rounded-xl shadow-sm border border-gray-100">
                    <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="mt-4 text-gray-500 text-lg">Produk tidak ditemukan. Coba kata kunci lain.</p>
                    <a href="{{ route('home') }}" class="mt-4 inline-block text-emerald-600 font-bold hover:underline">Lihat Semua Produk</a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
