<x-app-layout>
    <div class="py-12 bg-white md:bg-transparent">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-serif font-bold text-center text-emerald-950 mb-10">Keranjang Belanja</h1>
            
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100 p-6 md:p-8">
                @if(session('success'))
                    <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-400 p-4 rounded-r-lg shadow-sm animate-pulse-once">
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

                @if(count($cart) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-emerald-900 border-b-2 border-emerald-100 text-sm uppercase tracking-wider font-bold">
                                    <th class="p-4 bg-emerald-50/50 rounded-tl-lg">Produk</th>
                                    <th class="p-4 bg-emerald-50/50">Harga</th>
                                    <th class="p-4 bg-emerald-50/50 text-center">Jumlah</th>
                                    <th class="p-4 bg-emerald-50/50 text-right">Subtotal</th>
                                    <th class="p-4 bg-emerald-50/50 rounded-tr-lg text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @php $total = 0; @endphp
                                @foreach($cart as $id => $details)
                                    @php $total += $details['price'] * $details['quantity']; @endphp
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="p-4">
                                            <div class="flex items-center gap-4 min-w-[250px]">
                                                <div class="w-20 h-20 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                                    <img src="{{ $details['image'] ? asset('storage/' . $details['image']) : 'https://via.placeholder.com/100' }}" class="w-full h-full object-cover">
                                                </div>
                                                <div class="font-medium text-gray-900">{{ $details['name'] }}</div>
                                            </div>
                                        </td>
                                        <td class="p-4 whitespace-nowrap text-gray-600">
                                            @if(!empty($details['has_discount']))
                                                <div class="flex flex-col">
                                                    <span class="text-xs text-gray-400 line-through">Rp {{ number_format($details['original_price'], 0, ',', '.') }}</span>
                                                    <span class="font-bold text-emerald-700">Rp {{ number_format($details['price'], 0, ',', '.') }}</span>
                                                    @if(!empty($details['discount_label']))
                                                        <span class="text-[10px] font-bold text-rose-600">{{ $details['discount_label'] }}</span>
                                                    @endif
                                                </div>
                                            @else
                                                Rp {{ number_format($details['price'], 0, ',', '.') }}
                                            @endif
                                        </td>
                                        <td class="p-4">
                                            <form action="{{ route('cart.update') }}" method="POST" class="flex items-center justify-center gap-2">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $id }}">
                                                <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1" class="w-16 text-center rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50">
                                                <button type="submit" class="p-2 text-emerald-600 hover:text-emerald-800 hover:bg-emerald-50 rounded-full transition" title="Update Jumlah">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                        <td class="p-4 whitespace-nowrap text-right font-bold text-emerald-700">Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</td>
                                        <td class="p-4 text-center">
                                            <form action="{{ route('cart.remove') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $id }}">
                                                <button type="submit" class="text-red-400 hover:text-red-600 p-2 hover:bg-red-50 rounded-full transition" title="Hapus Produk">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-50/50">
                                    <td colspan="3" class="p-6 text-right font-bold text-lg text-gray-600">Total Pembayaran:</td>
                                    <td class="p-6 text-right font-bold text-2xl text-emerald-600 whitespace-nowrap">Rp {{ number_format($total, 0, ',', '.') }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                        <a href="{{ route('home') }}" class="flex items-center text-gray-500 hover:text-emerald-600 font-medium transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Lanjut Belanja
                        </a>
                        <a href="{{ route('checkout.index') }}" class="px-8 py-4 bg-gradient-to-r from-emerald-600 to-emerald-500 text-white font-bold rounded-full shadow-lg hover:shadow-xl hover:from-emerald-700 hover:to-emerald-600 transform hover:-translate-y-1 transition-all">
                            Checkout Sekarang
                        </a>
                    </div>
                @else
                    <div class="text-center py-20">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-serif font-bold text-gray-800 mb-2">Keranjang Anda Kosong</h2>
                        <p class="text-gray-500 mb-8 max-w-md mx-auto">Sepertinya Anda belum menemukan barang yang cocok. Yuk jelajahi koleksi kami!</p>
                        <a href="{{ route('home') }}" class="px-8 py-3 bg-gold-500 text-white font-bold rounded-full shadow-lg hover:bg-gold-600 transition">
                            Mulai Belanja
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
