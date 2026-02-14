<x-app-layout>
    <div class="py-12 bg-white md:bg-transparent">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-serif font-bold text-gray-800 mb-8">Dashboard Admin</h1>
            
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <!-- Orders Card -->
                <div class="bg-white rounded-3xl p-6 shadow-xl border border-gray-100 relative overflow-hidden group hover:-translate-y-1 transition duration-300">
                    <div class="absolute right-0 top-0 h-full w-20 bg-indigo-50 transform skew-x-12 translate-x-10 group-hover:translate-x-5 transition duration-500"></div>
                    <div class="relative z-10">
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Total Pesanan Hari Ini</p>
                        <div class="flex items-center gap-4">
                             <div class="p-3 bg-indigo-100 rounded-2xl text-indigo-600 shadow-sm">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </div>
                            <p class="text-4xl font-serif font-bold text-gray-800">{{ $todayOrders }}</p>
                        </div>
                    </div>
                </div>

                <!-- Revenue Card -->
                <div class="bg-white rounded-3xl p-6 shadow-xl border border-gray-100 relative overflow-hidden group hover:-translate-y-1 transition duration-300">
                    <div class="absolute right-0 top-0 h-full w-20 bg-emerald-50 transform skew-x-12 translate-x-10 group-hover:translate-x-5 transition duration-500"></div>
                    <div class="relative z-10">
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Pendapatan Hari Ini</p>
                        <div class="flex items-center gap-4">
                             <div class="p-3 bg-emerald-100 rounded-2xl text-emerald-600 shadow-sm">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <p class="text-3xl font-serif font-bold text-emerald-700">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Critical Stock Card -->
                <div class="bg-white rounded-3xl p-6 shadow-xl border border-gray-100 relative overflow-hidden group hover:-translate-y-1 transition duration-300">
                    <div class="absolute right-0 top-0 h-full w-20 bg-red-50 transform skew-x-12 translate-x-10 group-hover:translate-x-5 transition duration-500"></div>
                    <div class="relative z-10">
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Stok Menipis</p>
                        <div class="flex items-center gap-4">
                             <div class="p-3 bg-red-100 rounded-2xl text-red-600 shadow-sm">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <p class="text-4xl font-serif font-bold text-gray-800">{{ $lowStockProducts->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Low Stock Table -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                <div class="p-8 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <div>
                        <h3 class="text-xl font-serif font-bold text-gray-800">Perlu Restock Segera</h3>
                        <p class="text-sm text-gray-500 mt-1">Produk dengan stok kurang dari 5.</p>
                    </div>
                    <div>
                         <a href="{{ route('admin.products.index') }}" class="text-sm font-bold text-emerald-600 hover:text-emerald-800 flex items-center gap-1 group">
                             Lihat Semua Produk
                             <svg class="w-4 h-4 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                         </a>
                    </div>
                </div>
                
                @if($lowStockProducts->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-gray-400 border-b border-gray-100 text-xs uppercase tracking-wider font-bold bg-white">
                                <th class="p-6">Nama Produk</th>
                                <th class="p-6">Sisa Stok</th>
                                <th class="p-6">Harga Satuan</th>
                                <th class="p-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($lowStockProducts as $product)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="p-6 font-bold text-gray-800">{{ $product->name }}</td>
                                    <td class="p-6">
                                        <div class="flex items-center gap-2">
                                            <div class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></div>
                                            <span class="text-sm font-bold text-red-600 bg-red-50 px-3 py-1 rounded-full">
                                                Sisa {{ $product->stock }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="p-6 text-gray-500 font-mono">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </td>
                                    <td class="p-6 text-right">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg text-sm font-bold hover:bg-indigo-100 transition">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                            Restock
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <p class="text-lg font-bold text-gray-800">Aman Terkendali!</p>
                    <p class="text-gray-500">Semua produk memiliki stok yang cukup.</p>
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
