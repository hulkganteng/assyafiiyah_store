<x-app-layout>
    <div class="py-12 bg-white md:bg-transparent">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8 text-center">
                <span class="inline-block py-1 px-3 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider mb-2">Lacak Pesanan</span>
                <h1 class="text-3xl font-serif font-bold text-emerald-950">Cek Status Order</h1>
                <p class="text-gray-500 mt-2 text-sm">Masukkan kode order dan nomor WhatsApp untuk melihat status pesanan.</p>
            </div>

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

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                <div class="p-6 md:p-8">
                    <form action="{{ route('orders.track.submit') }}" method="POST" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Kode Order</label>
                            <input type="text" name="order_code" required class="w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50" placeholder="Contoh: ORD-ABC123">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nomor WhatsApp</label>
                            <input type="text" name="shipping_phone" required class="w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50" placeholder="0812xxxxxx">
                        </div>
                        <button type="submit" class="w-full py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition">
                            Lacak Pesanan
                        </button>
                    </form>
                    <p class="text-xs text-gray-400 mt-4">Kode order ada di struk/WhatsApp konfirmasi pemesanan.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
