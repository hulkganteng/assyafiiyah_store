<x-app-layout>
    <div class="py-12 bg-white md:bg-transparent">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-serif font-bold text-gray-800 mb-8">Verifikasi Pembayaran</h1>
            
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                <div class="p-8 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-xl font-serif font-bold text-gray-800">Pembayaran Masuk</h3>
                    <p class="text-sm text-gray-500 mt-1">Verifikasi bukti pembayaran dari pelanggan.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white border-b border-gray-100 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                <th class="px-8 py-5">Order</th>
                                <th class="px-8 py-5">Metode</th>
                                <th class="px-8 py-5 text-center">Bukti</th>
                                <th class="px-8 py-5">Status</th>
                                <th class="px-8 py-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($payments as $payment)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-8 py-5">
                                    <div class="flex flex-col">
                                        <div class="font-bold text-emerald-900">{{ $payment->order->order_code }}</div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $payment->order->customer_name ?? $payment->order->user->name ?? 'Tamu' }}
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            {{ $payment->created_at->format('d M Y, H:i') }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700">
                                        {{ ucfirst(str_replace('_', ' ', $payment->method)) }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    @if($payment->proof_path)
                                        <a href="{{ asset('storage/'.$payment->proof_path) }}" target="_blank" class="inline-block relative group">
                                            <div class="w-16 h-16 rounded-xl bg-gray-100 border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition">
                                                <img src="{{ asset('storage/'.$payment->proof_path) }}" class="w-full h-full object-cover">
                                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                </div>
                                            </div>
                                        </a>
                                    @else
                                        <span class="text-sm text-gray-400 italic">Tanpa Bukti</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5">
                                    @if($payment->verified_status == 'pending')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                            <span class="w-2 h-2 rounded-full bg-yellow-500 mr-2 animate-pulse"></span>
                                            Menunggu
                                        </span>
                                    @elseif($payment->verified_status == 'approved')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                                            Diterima
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-right">
                                    @if($payment->verified_status == 'pending')
                                        <div class="flex justify-end gap-2">
                                            <form action="{{ route('admin.payments.verify', $payment) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm hover:shadow transition transform hover:-translate-y-0.5">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    Terima
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.payments.verify', $payment) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button class="inline-flex items-center px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 hover:text-red-700 text-xs font-bold rounded-lg border border-red-200 hover:border-red-300 transition transform hover:-translate-y-0.5">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <div class="text-xs text-gray-400">
                                            Diverifikasi {{ $payment->verified_at ? $payment->verified_at->diffForHumans() : '' }}
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center">
                                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-800">Tidak Ada Pembayaran Baru</h3>
                                    <p class="text-gray-500">Semua pembayaran telah diverifikasi.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($payments->hasPages())
                <div class="px-8 py-6 border-t border-gray-100 bg-gray-50/50">
                    {{ $payments->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
