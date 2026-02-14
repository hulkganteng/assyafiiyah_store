<x-app-layout>
    <div class="py-12 bg-white md:bg-transparent">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <h1 class="text-3xl font-serif font-bold text-gray-800">Tambah Produk Baru</h1>
                 <a href="{{ route('admin.products.index') }}" class="inline-flex items-center text-gray-500 hover:text-emerald-600 font-bold transition">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                <div class="p-8 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-xl font-serif font-bold text-gray-800">Detail Produk</h3>
                </div>
                <div class="p-8">
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Nama Produk</label>
                                <input type="text" name="name" id="name" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50" required placeholder="Contoh: Kitab Jurumiyah">
                            </div>
                            
                            <div>
                                <label for="category_id" class="block text-sm font-bold text-gray-700 mb-2">Kategori</label>
                                <select name="category_id" id="category_id" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50">
                                    @foreach($categories as $cat)
                                        <option value="{{$cat->id}}">{{$cat->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="price" class="block text-sm font-bold text-gray-700 mb-2">Harga (Rp)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" name="price" id="price" class="w-full pl-10 rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50" required placeholder="0">
                                </div>
                            </div>

                            <div>
                                <label for="stock" class="block text-sm font-bold text-gray-700 mb-2">Stok Awal</label>
                                <input type="number" name="stock" id="stock" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50" required placeholder="0">
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Produk</label>
                            <textarea name="description" id="description" rows="4" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50" placeholder="Jelaskan detail produk..."></textarea>
                        </div>

                        <div class="pt-4 border-t border-gray-100">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h4 class="text-lg font-serif font-bold text-gray-800">Varian Produk (Opsional)</h4>
                                    <p class="text-sm text-gray-500">Contoh: Warna (Merah, Biru) dan Ukuran (S, M, L).</p>
                                </div>
                                <button type="button" id="add-variant-row" class="px-4 py-2 bg-emerald-600 text-white text-sm font-bold rounded-lg shadow hover:bg-emerald-700 transition">
                                    + Tambah Varian
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Varian 1</label>
                                    <input type="text" name="option1_name" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50" placeholder="Contoh: Warna">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Varian 2 (Opsional)</label>
                                    <input type="text" name="option2_name" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50" placeholder="Contoh: Ukuran">
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="text-xs uppercase tracking-wider text-gray-500 border-b">
                                            <th class="py-2 pr-4">Varian 1</th>
                                            <th class="py-2 pr-4">Varian 2</th>
                                            <th class="py-2 pr-4">Harga</th>
                                            <th class="py-2 pr-4">Stok</th>
                                            <th class="py-2 text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="variant-rows"></tbody>
                                </table>
                            </div>
                        </div>

                        <div>
                            <label for="image" class="block text-sm font-bold text-gray-700 mb-2">Gambar Produk</label>
                            <input type="file" name="image" id="image" class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-emerald-50 file:text-emerald-700
                                hover:file:bg-emerald-100
                            "/>
                        </div>
                        
                        <div class="flex items-center gap-8 pt-4 border-t border-gray-100">
                            <!-- Pre Order Checkbox -->
                             <label class="inline-flex items-center cursor-pointer group">
                                <input type="hidden" name="is_preorder" value="0">
                                <div class="relative">
                                    <input type="checkbox" name="is_preorder" value="1" class="peer sr-only">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                </div>
                                <span class="ms-3 text-sm font-medium text-gray-700 group-hover:text-emerald-600">Pre Order System</span>
                            </label>

                             <label class="inline-flex items-center cursor-pointer text-sm font-medium text-gray-700">
                                <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500 mr-2">
                                Tampilkan Produk (Aktif)
                            </label>
                        </div>
        
                        <div class="pt-6">
                            <button type="submit" class="w-full md:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-700 hover:to-emerald-600 focus:outline-none transition transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Simpan Produk
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        const variantRows = document.getElementById('variant-rows');
        const addVariantRowBtn = document.getElementById('add-variant-row');
        let variantIndex = 0;

        function addVariantRow(values = {}) {
            const row = document.createElement('tr');
            row.className = 'border-b border-gray-100';
            row.innerHTML = `
                <td class="py-3 pr-4">
                    <input type="text" name="variant_value_1[]" value="${values.value1 || ''}" class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm" placeholder="Contoh: Merah">
                </td>
                <td class="py-3 pr-4">
                    <input type="text" name="variant_value_2[]" value="${values.value2 || ''}" class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm" placeholder="Contoh: L">
                </td>
                <td class="py-3 pr-4">
                    <input type="number" name="variant_price[]" value="${values.price || ''}" class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm" placeholder="0">
                </td>
                <td class="py-3 pr-4">
                    <input type="number" name="variant_stock[]" value="${values.stock || ''}" class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm" placeholder="0">
                </td>
                <td class="py-3 text-right">
                    <button type="button" class="text-rose-500 hover:text-rose-700 text-sm font-bold remove-variant">Hapus</button>
                </td>
            `;
            row.querySelector('.remove-variant').addEventListener('click', () => row.remove());
            variantRows.appendChild(row);
            variantIndex += 1;
        }

        addVariantRowBtn.addEventListener('click', () => addVariantRow());
    </script>
</x-app-layout>
