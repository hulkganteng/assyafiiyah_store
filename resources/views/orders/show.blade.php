<x-app-layout>
    <div class="py-12 bg-white md:bg-transparent">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8 text-center md:text-left">
                <span class="inline-block py-1 px-3 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider mb-2">Order Tracking</span>
                <h1 class="text-3xl md:text-4xl font-serif font-bold text-emerald-950">Pesanan #{{ $order->order_code }}</h1>
                <p class="text-gray-500 mt-1">Dibuat pada {{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>

            <!-- Notifications -->
            @if(session('success'))
                <div class="mb-8 bg-emerald-50 border-l-4 border-emerald-400 p-4 rounded-r-lg shadow-sm animate-pulse-once">
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Order Status -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                        <div class="p-6 md:p-8 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="text-xl font-serif font-bold text-gray-800">Status Pesanan</h3>
                        </div>
                        <div class="p-6 md:p-8">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                     @php
                                        $statuses = [
                                            'pending_payment' => ['label' => 'Menunggu Pembayaran', 'color' => 'bg-yellow-100 text-yellow-800 border-yellow-200', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                            'paid' => ['label' => 'Sudah Dibayar', 'color' => 'bg-emerald-100 text-emerald-800 border-emerald-200', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                            'processing' => ['label' => 'Sedang Diproses', 'color' => 'bg-blue-100 text-blue-800 border-blue-200', 'icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z'],
                                            'shipped' => ['label' => 'Sedang Dikirim', 'color' => 'bg-indigo-100 text-indigo-800 border-indigo-200', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4'],
                                            'completed' => ['label' => 'Selesai', 'color' => 'bg-gray-100 text-gray-800 border-gray-200', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                            'cancelled' => ['label' => 'Dibatalkan', 'color' => 'bg-red-100 text-red-800 border-red-200', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                        ];
                                        $info = $statuses[$order->status] ?? $statuses['pending_payment'];
                                    @endphp
                                    <div class="h-12 w-12 rounded-full {{ str_replace(['text-', 'border-'], ['bg-', 'border-transparent '], $info['color']) }} flex items-center justify-center shadow-inner">
                                        <svg class="w-6 h-6 text-current opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $info['icon'] }}"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-bold text-gray-900">{{ $info['label'] }}</h4>
                                        <p class="text-sm text-gray-500">Terakhir update: {{ $order->updated_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items List -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                        <div class="p-6 md:p-8 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="text-xl font-serif font-bold text-gray-800">Daftar Produk</h3>
                        </div>
                        <ul class="divide-y divide-gray-100">
                            @foreach($order->items as $item)
                                <li class="p-6 md:p-8 flex flex-col md:flex-row items-center gap-6 hover:bg-gray-50 transition">
                                    <div class="w-full md:w-24 h-24 bg-gray-100 rounded-xl overflow-hidden shadow-sm border border-gray-200 flex-shrink-0">
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
                            <div class="flex justify-between items-center pt-2 border-t border-emerald-100 bg-white p-4 rounded-xl shadow-sm">
                                <span class="text-lg font-bold text-gray-700">Total Tagihan</span>
                                <span class="text-2xl font-bold text-emerald-700">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Sidebar Info -->
                <div class="space-y-8">
                    
                    <!-- Customer Details -->
                     <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="text-lg font-serif font-bold text-gray-800">Informasi Pengiriman</h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <div>
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Penerima</h4>
                                <p class="text-base font-bold text-emerald-900">{{ $order->customer_name ?? 'Tamu' }}</p>
                                <p class="text-sm text-gray-500">{{ $order->customer_email }}</p>
                                <p class="text-sm text-gray-500">{{ $order->shipping_phone }}</p>
                            </div>
                            <div class="border-t border-gray-100 pt-4">
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Alamat</h4>
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
                            <div class="border-t border-gray-100 pt-4">
                                 <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Metode Pengiriman</h4>
                                 <span class="inline-block px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-bold">
                                     {{ $order->shipping_method == 'pickup' ? 'Ambil Sendiri' : 'Kurir Ekspedisi' }}
                                 </span>
                            </div>
                             @if($order->notes)
                                <div class="border-t border-gray-100 pt-4">
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Catatan</h4>
                                    <p class="text-sm text-gray-500 italic">"{{ $order->notes }}"</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Receipt -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="text-lg font-serif font-bold text-gray-800">Struk Pembayaran</h3>
                        </div>
                        <div class="p-6">
                            @if($order->payment || $order->status != 'pending_payment')
                                <a href="{{ route('orders.payment-receipt', $order) }}" target="_blank" class="inline-flex items-center justify-center w-full px-4 py-3 rounded-xl bg-emerald-600 text-white font-bold shadow hover:bg-emerald-700 transition">
                                    Unduh PDF Struk Pembayaran
                                </a>
                                <p class="text-xs text-gray-500 mt-3">Struk tersedia setelah pembayaran diproses.</p>
                            @else
                                <div class="text-sm text-gray-500">Struk akan tersedia setelah Anda mengunggah bukti pembayaran.</div>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Verification Status -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="text-lg font-serif font-bold text-gray-800">Status Verifikasi Pembayaran</h3>
                        </div>
                        <div class="p-6">
                            @php
                                $paymentStatus = $order->payment ? $order->payment->verified_status : null;
                            @endphp
                            @if($paymentStatus === 'approved')
                                <div class="p-4 bg-emerald-50 text-emerald-800 rounded-xl border border-emerald-200 text-sm">
                                    Pembayaran Anda sudah di-ACC admin.
                                </div>
                            @elseif($paymentStatus === 'rejected')
                                <div class="p-4 bg-red-50 text-red-700 rounded-xl border border-red-200 text-sm">
                                    Bukti pembayaran ditolak. Silakan unggah ulang.
                                </div>
                            @elseif($paymentStatus === 'pending')
                                <div class="p-4 bg-yellow-50 text-yellow-800 rounded-xl border border-yellow-200 text-sm">
                                    Bukti pembayaran sedang diverifikasi admin.
                                </div>
                            @else
                                <div class="text-sm text-gray-500">Belum ada bukti pembayaran yang diunggah.</div>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Action Box -->
                    @if($order->status == 'pending_payment' && $order->payment_method == 'bank_transfer')
                        <div id="payment-section" class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border-2 border-gold-400 relative">
                            <div class="absolute top-0 inset-x-0 h-1 bg-gold-400"></div>
                            <div class="p-6">
                                <div class="text-center mb-6">
                                    <div class="w-12 h-12 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-800">Menunggu Pembayaran</h3>
                                    <p class="text-sm text-gray-500 mt-1">Silakan transfer sesuai nominal ke salah satu rekening di bawah ini:</p>
                                </div>
                                
                                <div class="bg-gray-50 rounded-xl p-4 mb-6 border border-gray-200">
                                    <h4 class="text-sm font-bold text-gray-700 mb-3">Rekening Transfer Aktif</h4>
                                    @if($bankAccounts->count() > 0)
                                        @foreach($bankAccounts as $account)
                                            <div class="flex justify-between items-center py-2 border-b border-dashed border-gray-300 last:border-b-0">
                                                <span class="font-bold text-gray-600">{{ $account->bank_name }}</span>
                                                <div class="text-right">
                                                    <span class="block font-mono font-bold text-lg text-emerald-800">{{ $account->account_number }}</span>
                                                    @if($account->account_name)
                                                        <span class="text-xs text-gray-400">a.n {{ $account->account_name }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="text-sm text-gray-500">Belum ada rekening aktif. Hubungi admin.</div>
                                    @endif
                                </div>

                                @if(!$order->payment || $order->payment->verified_status == 'rejected')
                                    <form action="{{ route('orders.pay', $order) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="method" value="bank_transfer">
                                        
                                        <div class="mb-4">
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Upload Bukti Transfer</label>
                                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:bg-gray-50 transition cursor-pointer group hover:border-emerald-400">
                                                <div class="space-y-1 text-center">
                                                    <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-emerald-500 transition" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                    <div class="flex text-sm text-gray-600 justify-center">
                                                        <label for="proof" class="relative cursor-pointer rounded-md font-medium text-emerald-600 hover:text-emerald-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-emerald-500">
                                                            <span>Pilih File</span>
                                                            <input id="proof" name="proof" type="file" class="sr-only" required accept="image/*">
                                                        </label>
                                                    </div>
                                                    <p class="text-xs text-gray-500">JPG, PNG up to 2MB</p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <button type="submit" class="w-full py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gold-500 hover:bg-gold-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gold-500 transition-all transform hover:-translate-y-1">
                                            Kirim Bukti Pembayaran
                                        </button>
                                    </form>
                                @elseif($order->payment->verified_status == 'pending')
                                    <div class="p-4 bg-yellow-50 text-yellow-800 rounded-xl border border-yellow-200 text-sm flex gap-3">
                                         <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <div>
                                            <strong>Sedang Diverifikasi</strong><br>
                                            Terima kasih! Bukti pembayaran Anda sedang dicek oleh admin kami.
                                        </div>
                                    </div>
                                    <a href="{{ asset('storage/' . $order->payment->proof_path) }}" target="_blank" class="block w-full text-center mt-3 text-sm text-gold-600 hover:underline font-bold">Lihat Bukti Upload</a>
                                @endif
                            </div>
                        </div>
                    @else
                        <!-- Simple contact card for help -->
                        <div id="payment-section" class="bg-emerald-800 rounded-3xl p-6 text-white text-center shadow-lg">
                           <h4 class="font-bold text-lg mb-2">Butuh Bantuan?</h4>
                           <p class="text-emerald-200 text-sm mb-4">Jika ada masalah dengan pesanan Anda, hubungi kami via WhatsApp.</p>
                           <a href="#" class="inline-block px-6 py-2 bg-emerald-600 rounded-full font-bold hover:bg-emerald-500 transition">Hubungi Admin</a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
