<x-app-layout>
    <div class="py-12 bg-white md:bg-transparent">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-serif font-bold text-center text-emerald-950 mb-10">Checkout</h1>
            
            <form action="{{ route('checkout.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- Form Fields -->
                    <div class="lg:col-span-2 space-y-6">
                        
                        <!-- Customer Info -->
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100 p-8">
                            <h3 class="text-xl font-serif font-bold text-emerald-800 mb-6 flex items-center">
                                <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mr-3 text-sm">1</span>
                                Informasi Pelanggan
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                                    <input type="text" name="customer_name" required class="w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50" placeholder="Contoh: Ahmad Fulan">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Email (Opsional)</label>
                                    <input type="email" name="customer_email" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50" placeholder="email@contoh.com">
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Info -->
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100 p-8">
                            <h3 class="text-xl font-serif font-bold text-emerald-800 mb-6 flex items-center">
                                <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mr-3 text-sm">2</span>
                                Detail Pengiriman
                            </h3>
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Nomor WhatsApp (Aktif)</label>
                                    <input type="text" name="shipping_phone" required class="w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50" placeholder="0812xxxxxx">
                                </div>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <label class="block text-sm font-bold text-gray-700">Alamat Pengiriman</label>
                                        <span class="text-xs text-gray-400">Wajib diisi untuk pengiriman kurir</span>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 mb-2">Provinsi</label>
                                            <select id="province_id" name="shipping_province_id" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm bg-gray-50">
                                                <option value="">-- Pilih Provinsi --</option>
                                                @if(!empty($provinces) && $provinces->count() > 0)
                                                    @foreach($provinces as $province)
                                                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <input type="hidden" name="shipping_province" id="province_name">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 mb-2">Kota/Kabupaten</label>
                                            <select id="city_id" name="shipping_city_id" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm bg-gray-50" disabled>
                                                <option value="">-- Pilih Kota/Kabupaten --</option>
                                            </select>
                                            <input type="hidden" name="shipping_city" id="city_name">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 mb-2">Kecamatan</label>
                                            <select id="district_id" name="shipping_district_id" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm bg-gray-50" disabled>
                                                <option value="">-- Pilih Kecamatan --</option>
                                            </select>
                                            <input type="hidden" name="shipping_district" id="district_name">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 mb-2">Kelurahan/Desa</label>
                                            <select id="village_id" name="shipping_village_id" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm bg-gray-50" disabled>
                                                <option value="">-- Pilih Kelurahan/Desa --</option>
                                            </select>
                                            <input type="hidden" name="shipping_village" id="village_name">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 mb-2">Kode Pos</label>
                                            <input type="text" id="postal_code" name="shipping_postal_code" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm bg-gray-50" placeholder="Contoh: 61151">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-bold text-gray-500 mb-2">Detail Alamat</label>
                                            <textarea name="shipping_address_detail" rows="2" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50 text-sm" placeholder="Nama jalan, nomor rumah, RT/RW, patokan, dll"></textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Shipping Method Selection -->
                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-sm font-bold text-gray-700 mb-4">Metode Pengiriman</label>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                        <!-- Option 1: Pickup -->
                                        <label class="cursor-pointer relative">
                                            <input type="radio" name="shipping_method_selection" value="pickup" class="peer sr-only" onchange="toggleShipping('pickup')">
                                            <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 hover:border-emerald-300 transition-all">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012 2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                                    </div>
                                                    <div>
                                                        <span class="block font-bold text-gray-800">Ambil Sendiri</span>
                                                        <span class="text-xs text-gray-500">Gratis Ongkir</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>

                                        <!-- Option 2: J&T Delivery -->
                                        <label class="cursor-pointer relative">
                                            <input type="radio" name="shipping_method_selection" value="delivery" class="peer sr-only" checked onchange="toggleShipping('delivery')">
                                            <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 hover:border-emerald-300 transition-all">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                                                       <span class="font-bold text-xs">J&T</span>
                                                    </div>
                                                    <div>
                                                        <span class="block font-bold text-gray-800">J&T Express</span>
                                                        <span class="text-xs text-gray-500">Hitung Otomatis</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>

                                    <!-- J&T Calculator Section -->
                                    <div id="jnt-calculator" class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                                        <h4 class="font-bold text-gray-700 mb-2 flex items-center gap-2">
                                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            Ongkir Otomatis
                                        </h4>
                                        <p class="text-xs text-gray-500 mb-4">Ongkir dihitung berdasarkan alamat yang dipilih.</p>

                                        <!-- Result -->
                                        <div id="shipping-result" class="mt-2 hidden p-3 bg-white rounded-lg border border-emerald-100">
                                            <div class="flex justify-between items-center text-sm">
                                                <span class="text-gray-600" id="distance-text">Alamat: -</span>
                                                <span class="font-bold text-emerald-600" id="price-text">Rp -</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hidden Inputs -->
                                    <input type="hidden" name="shipping_method" id="shipping_method" value="J&T Express">
                                    <input type="hidden" name="shipping_cost" id="shipping_cost_input" value="0">
                                    <input type="hidden" name="notes" id="notes_input">
                                </div>
                                
                                <div class="col-span-1 md:col-span-2">
                                     <label class="block text-sm font-bold text-gray-700 mb-2">Catatan Pesanan (Opsional)</label>
                                     <textarea onchange="document.getElementById('notes_input').value = this.value" rows="2" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50" placeholder="Pesan khusus..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1 space-y-6">
                        <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl border border-gray-100 sticky top-24 relative">
                           <div class="absolute top-0 inset-x-0 h-2 bg-gradient-to-r from-emerald-500 to-emerald-400"></div>
                            <div class="p-8">
                                <h3 class="text-xl font-serif font-bold text-emerald-900 mb-6">Ringkasan Pesanan</h3>
                                
                                <ul class="divide-y divide-dashed divide-gray-200 mb-6 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                                    @php $total = 0; @endphp
                                    @foreach($cart as $details)
                                        @php $total += $details['price'] * $details['quantity']; @endphp
                                        <li class="py-4 flex gap-4">
                                            <img src="{{ $details['image'] ? asset('storage/' . $details['image']) : 'https://via.placeholder.com/60' }}" class="w-14 h-14 object-cover rounded-md border border-gray-200">
                                            <div class="flex-1">
                                                <h4 class="font-bold text-gray-800 text-sm line-clamp-2">{{ $details['name'] }}</h4>
                                                <div class="flex justify-between mt-1 text-sm">
                                                    <span class="text-gray-500">{{ $details['quantity'] }}x</span>
                                                    <div class="text-right">
                                                        @if(!empty($details['has_discount']))
                                                            <div class="text-xs text-gray-400 line-through">Rp {{ number_format($details['original_price'] * $details['quantity'], 0, ',', '.') }}</div>
                                                        @endif
                                                        <div class="font-medium text-emerald-700">Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                
                                <div class="border-t-2 border-dashed border-gray-200 pt-4 mb-6 space-y-2">
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-600">Subtotal Produk</span>
                                        <span class="font-bold text-gray-800">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-600">Ongkos Kirim</span>
                                        <span class="font-bold text-emerald-600" id="summary-shipping">Rp 0</span>
                                    </div>
                                    <div class="border-t border-gray-100 pt-2 flex justify-between items-center">
                                        <span class="text-base font-bold text-gray-600">Total Tagihan</span>
                                        <!-- Store Base Total in Data Attribute -->
                                        <span class="text-2xl font-bold text-emerald-600" id="summary-total" data-base="{{ $total }}">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                <div class="mb-8">
                                    <label class="block text-sm font-bold text-gray-700 mb-3">Metode Pembayaran</label>
                                    <div class="space-y-3">
                                        <label class="relative flex py-3 px-4 rounded-xl border cursor-pointer hover:bg-emerald-50 hover:border-emerald-200 transition-all group">
                                            <input type="radio" name="payment_method" value="bank_transfer" class="peer sr-only" checked>
                                            <div class="flex items-center gap-3 w-full">
                                                <div class="w-5 h-5 rounded-full border border-gray-300 peer-checked:bg-emerald-600 peer-checked:border-emerald-600 flex items-center justify-center">
                                                    <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                                </div>
                                                <div class="flex-1">
                                                    <span class="block font-bold text-gray-800 text-sm">Transfer Bank</span>
                                                    <span class="block text-xs text-gray-500">BCA / BSI (Upload Bukti)</span>
                                                </div>
                                            </div>
                                            <div class="absolute inset-0 border-2 border-emerald-500 rounded-xl opacity-0 peer-checked:opacity-100 pointer-events-none"></div>
                                        </label>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 mb-8">
                                    <h4 class="text-sm font-bold text-gray-700 mb-3">Rekening Transfer Aktif</h4>
                                    @if($bankAccounts->count() > 0)
                                        @foreach($bankAccounts as $account)
                                            <div class="flex justify-between items-center py-2 border-b border-dashed border-gray-200 last:border-b-0">
                                                <div class="text-sm font-bold text-gray-700">{{ $account->bank_name }}</div>
                                                <div class="text-right">
                                                    <div class="text-sm font-mono font-bold text-emerald-800">{{ $account->account_number }}</div>
                                                    @if($account->account_name)
                                                        <div class="text-xs text-gray-500">a.n {{ $account->account_name }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="text-sm text-gray-500">Belum ada rekening aktif. Hubungi admin.</div>
                                    @endif
                                </div>

                                <button type="submit" class="w-full py-4 text-white bg-gold-500 hover:bg-gold-600 font-bold text-lg rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all flex justify-center items-center gap-2">
                                    <span>Buat Pesanan</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </button>
                                
                                <p class="text-center text-xs text-gray-400 mt-4 px-4">
                                    Dengan memesan, Anda menyetujui syarat & ketentuan Assyafiiyah Store.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>
    <script>
        const ADDRESS_FIELDS = [
            'province_id',
            'city_id',
            'district_id',
            'village_id',
            'postal_code',
        ];

        function setAddressFieldsEnabled(isEnabled) {
            ADDRESS_FIELDS.forEach((id) => {
                const el = document.getElementById(id);
                if (!el) return;
                el.disabled = !isEnabled;
                el.required = isEnabled;
            });
            const detail = document.querySelector('textarea[name="shipping_address_detail"]');
            if (detail) {
                detail.disabled = !isEnabled;
                detail.required = isEnabled;
            }
        }

        function resetAddressValues() {
            const province = document.getElementById('province_id');
            const city = document.getElementById('city_id');
            const district = document.getElementById('district_id');
            const village = document.getElementById('village_id');
            const postal = document.getElementById('postal_code');
            const detail = document.querySelector('textarea[name="shipping_address_detail"]');

            if (province) province.value = '';
            if (city) {
                city.value = '';
                city.disabled = true;
            }
            if (district) {
                district.value = '';
                district.disabled = true;
            }
            if (village) {
                village.value = '';
                village.disabled = true;
            }
            if (postal) postal.value = '';
            if (detail) detail.value = '';

            const hiddenIds = ['province_name', 'city_name', 'district_name', 'village_name'];
            hiddenIds.forEach((id) => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });
        }

        function setSelectOptions(select, items, placeholder) {
            select.innerHTML = '';
            const defaultOpt = document.createElement('option');
            defaultOpt.value = '';
            defaultOpt.textContent = placeholder;
            select.appendChild(defaultOpt);
                items.forEach((item) => {
                    const opt = document.createElement('option');
                    opt.value = item.id;
                    opt.textContent = item.name;
                    select.appendChild(opt);
                });
            }

        async function fetchOptions(url, select, placeholder) {
            try {
                const res = await fetch(url);
                if (!res.ok) {
                    throw new Error('Request failed: ' + res.status);
                }
                const data = await res.json();
                setSelectOptions(select, data, placeholder);
            } catch (err) {
                console.error('Gagal memuat lokasi:', err);
                if (select.options.length <= 1) {
                    setSelectOptions(select, [], placeholder + ' (Gagal memuat)');
                }
            }
        }

        function setNameFromSelect(selectId, hiddenId) {
            const select = document.getElementById(selectId);
            const hidden = document.getElementById(hiddenId);
            if (!select || !hidden) return;
            const selected = select.options[select.selectedIndex];
            hidden.value = selected && selected.value ? selected.textContent : '';
        }

        async function initLocationSelectors() {
            const province = document.getElementById('province_id');
            const city = document.getElementById('city_id');
            const district = document.getElementById('district_id');
            const village = document.getElementById('village_id');
            const postal = document.getElementById('postal_code');

            if (!province) return;

            await fetchOptions("{{ route('locations.provinces') }}", province, '-- Pilih Provinsi --');
            setNameFromSelect('province_id', 'province_name');

            province.addEventListener('change', async () => {
                setNameFromSelect('province_id', 'province_name');
                city.disabled = true;
                district.disabled = true;
                village.disabled = true;
                postal.value = '';
                setSelectOptions(city, [], '-- Pilih Kota/Kabupaten --');
                setSelectOptions(district, [], '-- Pilih Kecamatan --');
                setSelectOptions(village, [], '-- Pilih Kelurahan/Desa --');
                document.getElementById('city_name').value = '';
                document.getElementById('district_name').value = '';
                document.getElementById('village_name').value = '';

                if (!province.value) return;
                city.disabled = false;
                await fetchOptions(`{{ url('/locations/cities') }}/${province.value}`, city, '-- Pilih Kota/Kabupaten --');
                calculateShippingFromAddress();
            });

            city.addEventListener('change', async () => {
                setNameFromSelect('city_id', 'city_name');
                district.disabled = true;
                village.disabled = true;
                postal.value = '';
                setSelectOptions(district, [], '-- Pilih Kecamatan --');
                setSelectOptions(village, [], '-- Pilih Kelurahan/Desa --');
                document.getElementById('district_name').value = '';
                document.getElementById('village_name').value = '';

                if (!city.value) return;
                district.disabled = false;
                await fetchOptions(`{{ url('/locations/districts') }}/${city.value}`, district, '-- Pilih Kecamatan --');
                calculateShippingFromAddress();
            });

            district.addEventListener('change', async () => {
                setNameFromSelect('district_id', 'district_name');
                village.disabled = true;
                postal.value = '';
                setSelectOptions(village, [], '-- Pilih Kelurahan/Desa --');
                document.getElementById('village_name').value = '';

                if (!district.value) return;
                village.disabled = false;
                await fetchOptions(`{{ url('/locations/villages') }}/${district.value}`, village, '-- Pilih Kelurahan/Desa --');
            });

            village.addEventListener('change', () => {
                setNameFromSelect('village_id', 'village_name');
                calculateShippingFromAddress();
            });
        }

        const SHIPPING_BY_CITY = {
            'gresik': 10000,
            'surabaya': 15000,
            'lamongan': 15000,
            'sidoarjo': 18000,
            'malang': 25000,
            'mojokerto': 20000,
            'jakarta': 35000,
        };

        const RATE_JATIM = 25000;
        const RATE_OUTSIDE_JATIM = 50000;
        
        function formatRupiah(amount) {
            return 'Rp ' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function toggleShipping(method) {
            const calcDiv = document.getElementById('jnt-calculator');
            const costInput = document.getElementById('shipping_cost_input');
            const methodInput = document.getElementById('shipping_method');
            
            if (method === 'pickup') {
                calcDiv.classList.add('hidden');
                updateCost(0);
                methodInput.value = 'Ambil Sendiri (Pickup)';
                setAddressFieldsEnabled(false);
                resetAddressValues();
                document.getElementById('shipping-result').classList.add('hidden');
            } else {
                calcDiv.classList.remove('hidden');
                // Reset cost when switching back to delivery until calc
                updateCost(0);
                document.getElementById('shipping-result').classList.add('hidden');
                methodInput.value = 'J&T Express';
                setAddressFieldsEnabled(true);
                calculateShippingFromAddress();
            }
        }

        function updateCost(cost) {
            document.getElementById('shipping_cost_input').value = cost;
            document.getElementById('summary-shipping').innerText = formatRupiah(cost);
            
            const baseTotal = parseFloat(document.getElementById('summary-total').dataset.base);
            const grandTotal = baseTotal + cost;
            
            document.getElementById('summary-total').innerText = formatRupiah(grandTotal);
        }

        function showResult(distText, cost) {
            const resDiv = document.getElementById('shipping-result');
            resDiv.classList.remove('hidden');
            document.getElementById('distance-text').innerText = 'Alamat: ' + distText;
            document.getElementById('price-text').innerText = formatRupiah(cost);
        }

        function normalizePlace(name) {
            if (!name) return '';
            return name
                .toLowerCase()
                .replace('kabupaten', '')
                .replace('kota', '')
                .replace('kab.', '')
                .replace('kab', '')
                .replace(/\s+/g, ' ')
                .trim();
        }

        function calculateShippingFromAddress() {
            const shippingMethod = document.getElementById('shipping_method').value;
            if (shippingMethod.toLowerCase().includes('pickup')) {
                return;
            }

            const cityName = document.getElementById('city_name').value;
            const provinceName = document.getElementById('province_name').value;
            if (!cityName && !provinceName) {
                updateCost(0);
                document.getElementById('shipping-result').classList.add('hidden');
                return;
            }

            const cityKey = normalizePlace(cityName);
            let cost = SHIPPING_BY_CITY[cityKey] || 0;

            if (!cost) {
                const provKey = normalizePlace(provinceName);
                if (provKey.includes('jawa timur')) {
                    cost = RATE_JATIM;
                } else if (provKey) {
                    cost = RATE_OUTSIDE_JATIM;
                }
            }

            if (cost) {
                const label = cityName && provinceName ? `${cityName}, ${provinceName}` : (cityName || provinceName);
                showResult(label, cost);
            } else {
                document.getElementById('shipping-result').classList.add('hidden');
            }
            updateCost(cost);
        }

        document.addEventListener('DOMContentLoaded', () => {
            initLocationSelectors();
            setAddressFieldsEnabled(true);
            calculateShippingFromAddress();
        });
    </script>
</x-app-layout>
