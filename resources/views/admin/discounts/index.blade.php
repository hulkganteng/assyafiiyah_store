<x-app-layout>
    <div class="py-12 bg-white md:bg-transparent">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div>
                    <h1 class="text-3xl font-serif font-bold text-gray-800">Manajemen Diskon</h1>
                    <p class="text-sm text-gray-500">Pilih beberapa produk lalu terapkan potongan harga.</p>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-400 p-4 rounded-r-lg shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-rose-50 border-l-4 border-rose-400 p-4 rounded-r-lg shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-rose-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v4a1 1 0 002 0V7zm-1 8a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-rose-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100 mb-6">
                <div class="p-6 md:p-8 border-b border-gray-100 bg-gray-50/50">
                    <form method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                        <div class="flex-1">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Cari Produk</label>
                            <input type="text" name="search" value="{{ request('search') }}" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50" placeholder="Nama produk...">
                        </div>
                        <div class="w-full md:w-64">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Kategori</label>
                            <select name="category" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg transition">
                            Filter
                        </button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                <th class="px-6 py-5 w-12 text-center">
                                    <input id="select-all-products" type="checkbox" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                </th>
                                <th class="px-8 py-5">Produk</th>
                                <th class="px-8 py-5">Kategori</th>
                                <th class="px-8 py-5">Harga</th>
                                <th class="px-8 py-5">Diskon Aktif</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($products as $product)
                                <tr class="hover:bg-gray-50 transition-colors group">
                                    <td class="px-6 py-5 text-center">
                                        <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" form="discount-form" class="product-checkbox rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12 rounded-xl bg-gray-100 border border-gray-200 overflow-hidden">
                                                @if($product->images->count() > 0)
                                                    <img class="h-12 w-12 object-cover transform group-hover:scale-110 transition duration-500" src="{{ asset('storage/' . $product->images->first()->path) }}" alt="">
                                                @else
                                                    <div class="h-full w-full flex items-center justify-center text-gray-300">
                                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900 group-hover:text-emerald-700 transition">{{ $product->name }}</div>
                                                <div class="text-xs text-gray-400">ID: {{ $product->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                         <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                                            {{ $product->category->name }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-sm font-bold text-emerald-900 font-mono">
                                        @if($product->has_active_discount)
                                            <span class="text-xs text-gray-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                            <div class="text-sm font-bold text-emerald-700">Rp {{ number_format($product->discounted_price, 0, ',', '.') }}</div>
                                        @else
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        @endif
                                    </td>
                                    <td class="px-8 py-5">
                                        @if($product->has_active_discount)
                                            <span class="inline-flex items-center text-xs font-bold text-rose-600 bg-rose-50 px-2 py-1 rounded-full">
                                                {{ $product->discount_label }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-20 text-center text-gray-500">
                                        <p class="text-lg font-medium text-gray-900">Belum ada produk</p>
                                        <p class="text-sm mb-6">Tambahkan produk terlebih dahulu.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($products->hasPages())
                    <div class="px-8 py-6 border-t border-gray-100 bg-gray-50/50">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>

            <form id="discount-form" action="{{ route('admin.discounts.apply') }}" method="POST" class="bg-white shadow-xl sm:rounded-3xl border border-gray-100 p-6">
                @csrf
                <div class="flex flex-col md:flex-row md:items-end gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tipe Potongan</label>
                        <select name="discount_type" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50">
                            <option value="">-- Pilih Tipe --</option>
                            <option value="percent">Persentase (%)</option>
                            <option value="fixed">Nominal Rupiah</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nominal Potongan</label>
                        <input type="number" name="discount_value" min="0" step="0.01" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50" placeholder="Contoh: 10 atau 5000">
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="clear_discount" value="1" id="clear-discount" class="rounded border-gray-300 text-rose-600 focus:ring-rose-500">
                        <label for="clear-discount" class="text-sm font-medium text-gray-600">Hapus Potongan</label>
                    </div>
                    <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg transition">
                        Terapkan Potongan
                    </button>
                </div>
                <p class="mt-3 text-xs text-gray-400">Pilih produk di atas, lalu atur potongan harga. Centang "Hapus Potongan" untuk menghapus diskon dari produk terpilih.</p>
            </form>
        </div>
    </div>

    <script>
        const selectAll = document.getElementById('select-all-products');
        const checkboxes = Array.from(document.querySelectorAll('.product-checkbox'));
        if (selectAll) {
            selectAll.addEventListener('change', () => {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
            });
        }
        checkboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                if (!selectAll) return;
                selectAll.checked = checkboxes.every(item => item.checked);
            });
        });
    </script>
</x-app-layout>
