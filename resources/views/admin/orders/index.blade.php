<x-app-layout>
    <div class="py-12 bg-white md:bg-transparent">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <h1 class="text-3xl font-serif font-bold text-gray-800">Kelola Pesanan</h1>
                
                <a href="{{ route('admin.reports.export') }}" class="inline-flex items-center px-6 py-3 border border-transparent rounded-full shadow-lg text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition transform hover:-translate-y-1">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export Laporan (CSV)
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                <th class="px-8 py-5">Info Pesanan</th>
                                <th class="px-8 py-5">Pelanggan</th>
                                <th class="px-8 py-5">Total Bayar</th>
                                <th class="px-8 py-5">Status</th>
                                <th class="px-8 py-5">Tanggal</th>
                                <th class="px-8 py-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($orders as $order)
                            <tr class="hover:bg-gray-50 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="font-bold text-emerald-900">{{ $order->order_code }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $order->items_count ?? $order->items->count() }} Item Barang</div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="font-bold text-gray-800">{{ $order->customer_name ?? $order->user->name ?? 'Tamu' }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $order->customer_email ?? $order->user->email ?? '-' }}</div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="font-bold text-emerald-700">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                                    <div class="text-xs text-gray-400 mt-1 uppercase">{{ $order->payment_method == 'cod' ? 'COD' : 'Transfer' }}</div>
                                </td>
                                <td class="px-8 py-5">
                                     @php
                                        $statusConfig = [
                                            'pending_payment' => ['color' => 'bg-yellow-100 text-yellow-800 border-yellow-200', 'label' => 'Belum Bayar'],
                                            'paid' => ['color' => 'bg-emerald-100 text-emerald-800 border-emerald-200', 'label' => 'Lunas'],
                                            'processing' => ['color' => 'bg-blue-100 text-blue-800 border-blue-200', 'label' => 'Diproses'],
                                            'shipped' => ['color' => 'bg-indigo-100 text-indigo-800 border-indigo-200', 'label' => 'Dikirim'],
                                            'completed' => ['color' => 'bg-gray-100 text-gray-800 border-gray-200', 'label' => 'Selesai'],
                                            'cancelled' => ['color' => 'bg-red-100 text-red-800 border-red-200', 'label' => 'Batal'],
                                        ];
                                        $config = $statusConfig[$order->status] ?? $statusConfig['pending_payment'];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $config['color'] }}">
                                        {{ $config['label'] }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-sm text-gray-500">
                                    {{ $order->created_at->format('d M Y') }}
                                    <div class="text-xs text-gray-400">{{ $order->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center justify-center w-10 h-10 bg-white border border-gray-200 rounded-full text-gray-400 hover:text-emerald-600 hover:border-emerald-200 hover:bg-emerald-50 transition shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-8 py-20 text-center">
                                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-800">Belum Ada Pesanan</h3>
                                    <p class="text-gray-500">Daftar pesanan dari pelanggan akan muncul di sini.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($orders->hasPages())
                <div class="px-8 py-6 border-t border-gray-100 bg-gray-50/50">
                    {{ $orders->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
