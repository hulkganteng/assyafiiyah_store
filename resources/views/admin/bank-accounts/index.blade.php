<x-app-layout>
    <div class="py-12 bg-white md:bg-transparent">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-serif font-bold text-gray-800">Rekening Bank</h1>
                    <p class="text-sm text-gray-500 mt-1">Kelola rekening aktif untuk pembayaran.</p>
                </div>
                <a href="{{ route('admin.bank-accounts.create') }}" class="inline-flex items-center px-5 py-3 bg-emerald-600 text-white font-bold rounded-xl shadow hover:bg-emerald-700 transition">
                    Tambah Rekening
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-serif font-bold text-gray-800">Daftar Rekening</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-gray-400 border-b border-gray-100 text-xs uppercase tracking-wider font-bold bg-white">
                                <th class="p-6">Bank</th>
                                <th class="p-6">No. Rekening</th>
                                <th class="p-6">Atas Nama</th>
                                <th class="p-6">Status</th>
                                <th class="p-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($accounts as $account)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="p-6 font-bold text-gray-800">{{ $account->bank_name }}</td>
                                    <td class="p-6 font-mono text-gray-700">{{ $account->account_number }}</td>
                                    <td class="p-6 text-gray-700">{{ $account->account_name ?? '-' }}</td>
                                    <td class="p-6">
                                        @if($account->is_active)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">Aktif</span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="p-6 text-right space-x-2">
                                        <a href="{{ route('admin.bank-accounts.edit', $account) }}" class="inline-flex items-center px-3 py-2 bg-indigo-50 text-indigo-700 rounded-lg text-sm font-bold hover:bg-indigo-100 transition">Edit</a>
                                        <form action="{{ route('admin.bank-accounts.destroy', $account) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-50 text-red-700 rounded-lg text-sm font-bold hover:bg-red-100 transition" onclick="return confirm('Hapus rekening ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="p-6 text-center text-gray-500" colspan="5">Belum ada rekening.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
