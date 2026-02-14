<x-app-layout>
    <div class="py-12 bg-white md:bg-transparent">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-serif font-bold text-gray-800">Tambah Kategori</h1>
                <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center text-gray-500 hover:text-emerald-600 font-bold transition">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                <div class="p-6 md:p-8 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-xl font-serif font-bold text-emerald-800">Form Kategori</h3>
                    <p class="text-sm text-gray-500 mt-1">Buat kategori produk baru.</p>
                </div>
                <div class="p-6 md:p-8">
                    <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Nama Kategori</label>
                            <input type="text" name="name" id="name" required
                                value="{{ old('name') }}"
                                class="w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50"
                                placeholder="Contoh: Kitab Kuning">
                            @error('name')
                                <p class="text-xs text-rose-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-700 hover:to-emerald-600 transition transform hover:-translate-y-1">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Simpan Kategori
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
