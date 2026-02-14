<x-app-layout>
    <div class="py-12 bg-white md:bg-transparent">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-3xl font-serif font-bold text-gray-800">Edit Rekening</h1>
                <p class="text-sm text-gray-500 mt-1">Perbarui detail rekening.</p>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100 p-8">
                <form action="{{ route('admin.bank-accounts.update', $bankAccount) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Bank</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name', $bankAccount->bank_name) }}" required class="w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Rekening</label>
                        <input type="text" name="account_number" value="{{ old('account_number', $bankAccount->account_number) }}" required class="w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Atas Nama</label>
                        <input type="text" name="account_name" value="{{ old('account_name', $bankAccount->account_name) }}" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50">
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" value="1" {{ $bankAccount->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-sm text-gray-600">Aktifkan rekening</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.bank-accounts.index') }}" class="px-5 py-3 rounded-xl border border-gray-200 text-gray-700 font-bold hover:bg-gray-50 transition">Batal</a>
                        <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
