<x-app-layout>
    <div class="py-12 bg-white md:bg-transparent">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <div>
                     <span class="inline-block py-1 px-3 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider mb-2">Detail Pesanan</span>
                    <h1 class="text-3xl font-serif font-bold text-gray-800">Order #{{ $order->order_code }}</h1>
                    <p class="text-sm text-gray-500">{{ $order->created_at->format('d F Y, H:i') }}</p>
                </div>
                <div>
                     <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center text-gray-500 hover:text-emerald-600 font-bold transition">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali
                    </a>
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Main Info -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Update Status -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                        <div class="p-6 md:p-8 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                            <h3 class="text-xl font-serif font-bold text-gray-800">Update Status</h3>
                            @php
                                $statusColors = [
                                    'pending_payment' => 'text-yellow-600 bg-yellow-100',
                                    'paid' => 'text-emerald-600 bg-emerald-100',
                                    'processing' => 'text-blue-600 bg-blue-100',
                                    'shipped' => 'text-indigo-600 bg-indigo-100',
                                    'completed' => 'text-gray-600 bg-gray-100',
                                    'cancelled' => 'text-red-600 bg-red-100',
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusColors[$order->status] ?? 'text-gray-600 bg-gray-100' }}">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </div>
                        <div class="p-6 md:p-8">
                            <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="flex flex-col sm:flex-row gap-4 items-end">
                                @csrf
                                @method('PUT')
                                <div class="w-full">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Status Baru</label>
                                    <div class="relative">
                                        <select name="status" class="w-full appearance-none rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 bg-white py-3 px-4 pr-8 leading-tight">
                                            @foreach(['pending_payment', 'paid', 'processing', 'shipped', 'completed', 'cancelled'] as $status)
                                                <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>
                                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-md transition transform hover:-translate-y-0.5 whitespace-nowrap">
                                    Simpan Perubahan
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                        <div class="p-6 md:p-8 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="text-xl font-serif font-bold text-gray-800">Daftar Barang</h3>
                        </div>
                        <ul class="divide-y divide-gray-100">
                            @foreach($order->items as $item)
                                <li class="p-6 md:p-8 flex flex-col md:flex-row items-center gap-6 hover:bg-gray-50 transition">
                                    <div class="w-20 h-20 bg-gray-100 rounded-xl overflow-hidden shadow-sm border border-gray-200 flex-shrink-0">
                                        <img src="{{ $item->product->images->first() ? asset('storage/' . $item->product->images->first()->path) : 'https://via.placeholder.com/100' }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 text-center md:text-left">
                                        <h4 class="text-lg font-bold text-emerald-900">{{ $item->product->name }}</h4>
                                        @if($item->variant_label)
                                            <p class="text-xs text-gray-500 mt-1">{{ $item->variant_label }}</p>
                                        @endif
                                        <p class="text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-emerald-600">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                         @php
                            $subtotal = $order->items->sum('subtotal');
                            $shippingCost = $order->shipping_cost ?? 0;
                         @endphp
                         <div class="p-6 md:p-8 bg-emerald-50/30 border-t border-gray-100 space-y-2">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">Subtotal Produk</span>
                                <span class="font-bold text-gray-800">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">Ongkos Kirim</span>
                                <span class="font-bold text-gray-800">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                            </div>
                            @if($order->has_discount)
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-rose-600 font-bold">{{ $order->discount_label }}</span>
                                    <span class="font-bold text-rose-600">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between items-center pt-2 border-t border-emerald-100">
                                <span class="text-lg font-bold text-gray-700">Total Tagihan</span>
                                <span class="text-2xl font-bold text-emerald-700">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Discount -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                        <div class="p-6 md:p-8 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="text-xl font-serif font-bold text-gray-800">Diskon Admin (Min. 2 Item)</h3>
                        </div>
                        <div class="p-6 md:p-8">
                            <form action="{{ route('admin.orders.discount', $order) }}" method="POST" class="flex flex-col md:flex-row md:items-end gap-4">
                                @csrf
                                <div class="flex-1">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Tipe Diskon</label>
                                    <select name="discount_type" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 bg-white">
                                        <option value="">-- Pilih Tipe --</option>
                                        <option value="percent" {{ $order->discount_type === 'percent' ? 'selected' : '' }}>Persentase (%)</option>
                                        <option value="fixed" {{ $order->discount_type === 'fixed' ? 'selected' : '' }}>Nominal Rupiah</option>
                                    </select>
                                </div>
                                <div class="flex-1">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Nominal Diskon</label>
                                    <input type="number" name="discount_value" min="0" step="0.01" value="{{ $order->discount_value }}" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50" placeholder="Contoh: 10 atau 5000">
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="clear_discount" value="1" id="clear-order-discount" class="rounded border-gray-300 text-rose-600 focus:ring-rose-500">
                                    <label for="clear-order-discount" class="text-sm font-medium text-gray-600">Hapus Diskon</label>
                                </div>
                                <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg transition">
                                    Terapkan Diskon
                                </button>
                            </form>
                            <p class="mt-3 text-xs text-gray-400">Diskon admin berlaku untuk pembelian minimal 2 item. Diskon dihitung dari subtotal produk (tidak termasuk ongkir).</p>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Details -->
                <div class="space-y-8">

                    <!-- Payment Receipt -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="text-lg font-serif font-bold text-gray-800">Struk Pembayaran</h3>
                        </div>
                        <div class="p-6">
                            <a href="{{ route('admin.orders.payment-receipt', $order) }}" target="_blank" class="inline-flex items-center justify-center w-full px-4 py-3 rounded-xl bg-emerald-600 text-white font-bold shadow hover:bg-emerald-700 transition">
                                Unduh PDF Struk 5.8 cm
                            </a>
                            <p class="text-xs text-gray-500 mt-3">Struk pembayaran sudah mencakup data pengiriman.</p>
                        </div>
                    </div>
                    
                    <!-- Customer Info -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="text-lg font-serif font-bold text-gray-800">Data Pelanggan</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Nama</h4>
                                <p class="text-base font-bold text-emerald-900">{{ $order->customer_name ?? $order->user->name ?? 'Guest' }}</p>
                            </div>
                             <div>
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Email</h4>
                                <p class="text-sm text-gray-700">{{ $order->customer_email ?? $order->user->email ?? '-' }}</p>
                            </div>
                            <div class="pt-4 border-t border-gray-100">
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Alamat Pengiriman</h4>
                                @php
                                    $addressLines = [];
                                    if ($order->shipping_address_detail) {
                                        $addressLines[] = $order->shipping_address_detail;
                                    }
                                    $regionParts = array_filter([
                                        $order->shipping_village,
                                        $order->shipping_district,
                                        $order->shipping_city,
                                        $order->shipping_province,
                                    ]);
                                    if (!empty($regionParts)) {
                                        $addressLines[] = implode(', ', $regionParts);
                                    }
                                    if ($order->shipping_postal_code) {
                                        $addressLines[] = 'Kode Pos ' . $order->shipping_postal_code;
                                    }
                                    if (empty($addressLines) && $order->shipping_address) {
                                        $addressLines[] = $order->shipping_address;
                                    }
                                @endphp
                                @foreach($addressLines as $line)
                                    <p class="text-sm text-gray-700 leading-relaxed">{{ $line }}</p>
                                @endforeach
                            </div>
                            <div class="pt-4 border-t border-gray-100">
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Telepon</h4>
                                <p class="text-sm text-gray-700">{{ $order->shipping_phone }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Info -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="text-lg font-serif font-bold text-gray-800">Informasi Pembayaran</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Metode</h4>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700">
                                     {{ $order->payment_method == 'cod' ? 'COD (Bayar Ditempat)' : 'Transfer Bank' }}
                                </span>
                            </div>
                            
                            @if($order->payment)
                                <div class="pt-4 border-t border-gray-100">
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Bukti Transfer</h4>
                                    <div class="mt-2">
                                        <span class="text-xs font-bold {{ $order->payment->verified_status == 'approved' ? 'text-green-600' : ($order->payment->verified_status == 'rejected' ? 'text-red-600' : 'text-yellow-600') }}">
                                            Status: {{ ucfirst($order->payment->verified_status) }}
                                        </span>
                                        <a href="{{ asset('storage/' . $order->payment->proof_path) }}" target="_blank" class="block mt-2 relative group rounded-lg overflow-hidden border border-gray-200">
                                            <img src="{{ asset('storage/' . $order->payment->proof_path) }}" class="w-full h-32 object-cover">
                                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                                <span class="text-white text-xs font-bold">Lihat</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endif
                            
                            @if($order->notes)
                                <div class="pt-4 border-t border-gray-100">
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Catatan</h4>
                                    <p class="text-sm text-gray-500 italic">"{{ $order->notes }}"</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
