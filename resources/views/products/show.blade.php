<x-app-layout>
    <div class="py-12 bg-white md:bg-transparent">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Breadcrumb -->
            <nav class="flex mb-8 text-sm text-gray-500" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center hover:text-emerald-600">
                        <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                        </svg>
                        Beranda
                    </a>
                    </li>
                    <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <a href="{{ route('home', ['category' => $product->category->slug]) }}" class="ml-1 hover:text-emerald-600">{{ $product->category->name }}</a>
                    </div>
                    </li>
                    <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ml-1 text-gray-800 font-medium md:truncate md:max-w-xs">{{ $product->name }}</span>
                    </div>
                    </li>
                </ol>
            </nav>

            <div class="bg-white md:rounded-3xl shadow-xl overflow-hidden border border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    
                    <!-- Image Section -->
                    <div class="p-8 bg-gray-50 flex flex-col items-center justify-center relative">
                        <!-- Main Image -->
                        <div class="w-full aspect-square relative rounded-2xl overflow-hidden shadow-lg border-4 border-white">
                            <img id="mainImage" src="{{ $product->images->first() ? asset('storage/' . $product->images->first()->path) : 'https://via.placeholder.com/600' }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
                        </div>
                        
                        <!-- Thumbnails -->
                        @if($product->images->count() > 1)
                            <div class="flex gap-4 mt-6 overflow-x-auto w-full justify-center pb-2">
                                @foreach($product->images as $image)
                                    <button onclick="document.getElementById('mainImage').src='{{ asset('storage/' . $image->path) }}'" class="shrink-0 w-20 h-20 rounded-lg border-2 border-transparent hover:border-emerald-500 focus:border-emerald-500 overflow-hidden transition">
                                        <img src="{{ asset('storage/' . $image->path) }}" class="w-full h-full object-cover">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Details Section -->
                    <div class="p-8 md:p-12 lg:p-16 flex flex-col justify-center">
                        @php
                            $hasVariants = $product->variants->count() > 0;
                            $variantMinPrice = $hasVariants ? (float) $product->variants->min('price') : (float) $product->price;
                            $variantStock = $hasVariants ? (int) $product->variants->sum('stock') : (int) $product->stock;
                            $displayPrice = $product->has_active_discount ? $product->discountPrice($variantMinPrice) : $variantMinPrice;
                        @endphp
                        <div class="uppercase tracking-widest text-xs text-gold-600 font-bold mb-4">{{ $product->category->name }}</div>
                        <h1 class="text-4xl md:text-5xl font-serif font-bold text-emerald-950 mb-6 leading-tight">{{ $product->name }}</h1>
                        
                        <div class="flex items-center mb-8">
                            @if($product->has_active_discount)
                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-400 line-through">Rp {{ number_format($variantMinPrice, 0, ',', '.') }}</span>
                                    <span class="text-3xl font-bold text-emerald-600">
                                        Rp {{ number_format($displayPrice, 0, ',', '.') }}
                                        @if($hasVariants)
                                            <span class="text-sm text-gray-500 font-medium">Mulai dari</span>
                                        @endif
                                    </span>
                                    <span class="mt-1 inline-flex items-center text-xs font-bold text-rose-600">{{ $product->discount_label }}</span>
                                </div>
                            @else
                                <span class="text-3xl font-bold text-emerald-600">
                                    Rp {{ number_format($displayPrice, 0, ',', '.') }}
                                    @if($hasVariants)
                                        <span class="text-sm text-gray-500 font-medium">Mulai dari</span>
                                    @endif
                                </span>
                            @endif
                            @if($variantStock > 0)
                                <span class="ml-6 px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full">Tersedia {{ $variantStock }} stok</span>
                            @else
                                <span class="ml-6 px-3 py-1 bg-red-100 text-red-800 text-xs font-bold rounded-full">Stok Habis</span>
                            @endif
                        </div>

                        <div class="prose prose-emerald text-gray-600 mb-10 leading-relaxed">
                            <p>{!! nl2br(e($product->description)) !!}</p>
                        </div>

                        <!-- Action Form -->
                        @if(!$hasVariants)
                            <form action="{{ route('cart.add') }}" method="POST" class="mt-auto pt-8 border-t border-gray-100">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                
                                <div class="flex items-end gap-6">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah</label>
                                        <div class="relative flex items-center max-w-[8rem]">
                                            <button type="button" onclick="this.parentNode.querySelector('input').stepDown()" class="bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-l-lg p-3 h-11 focus:ring-gray-100 focus:ring-2 focus:outline-none">
                                                <svg class="w-3 h-3 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16"/>
                                                </svg>
                                            </button>
                                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="bg-gray-50 border-x-0 border-gray-300 h-11 text-center text-gray-900 text-sm focus:ring-emerald-500 focus:border-emerald-500 block w-full py-2.5" required>
                                            <button type="button" onclick="this.parentNode.querySelector('input').stepUp()" class="bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-r-lg p-3 h-11 focus:ring-gray-100 focus:ring-2 focus:outline-none">
                                                <svg class="w-3 h-3 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <button type="submit"  class="flex-1 text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:outline-none focus:ring-emerald-300 font-bold rounded-lg text-lg px-5 py-2.5 text-center transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5" {{ $product->stock < 1 ? 'disabled' : '' }}>
                                        @if($product->stock > 0)
                                            + Tambah ke Keranjang
                                        @else
                                            Stok Habis
                                        @endif
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="mt-auto pt-8 border-t border-gray-100">
                                <button type="button" id="openVariantModal" class="w-full text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:outline-none focus:ring-emerald-300 font-bold rounded-lg text-lg px-5 py-3 text-center transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5" {{ $variantStock < 1 ? 'disabled' : '' }}>
                                    @if($variantStock > 0)
                                        Pilih Varian & Tambah ke Keranjang
                                    @else
                                        Stok Habis
                                    @endif
                                </button>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    @if($hasVariants)
        @php
            $variants = $product->variants->map(function ($variant) use ($product) {
                $basePrice = (float) $variant->price;
                return [
                    'id' => $variant->id,
                    'option1_value' => $variant->option1_value,
                    'option2_value' => $variant->option2_value,
                    'price' => $basePrice,
                    'display_price' => $product->has_active_discount ? $product->discountPrice($basePrice) : $basePrice,
                    'stock' => (int) $variant->stock,
                    'label' => $variant->label,
                ];
            })->values();
            $option1Name = $product->variants->first()->option1_name ?: 'Varian 1';
            $option2Name = $product->variants->first()->option2_name ?: 'Varian 2';
            $hasOption2 = $product->variants->pluck('option2_value')->filter()->count() > 0;
        @endphp
        <div id="variantModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4">
            <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-serif font-bold text-gray-800">Pilih Varian</h3>
                        <p class="text-xs text-gray-500">{{ $product->name }}</p>
                    </div>
                    <button type="button" id="closeVariantModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form action="{{ route('cart.add') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="variant_id" id="variantIdInput">

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">{{ $option1Name }}</label>
                        <select id="variantOption1" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50">
                            <option value="">-- Pilih {{ $option1Name }} --</option>
                        </select>
                    </div>

                    @if($hasOption2)
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">{{ $option2Name }}</label>
                            <select id="variantOption2" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50" disabled>
                                <option value="">-- Pilih {{ $option2Name }} --</option>
                            </select>
                        </div>
                    @endif

                    <div class="flex items-center justify-between text-sm text-gray-600">
                        <div>
                            <div class="text-xs uppercase tracking-wide text-gray-400">Harga</div>
                            <div class="text-lg font-bold text-emerald-700" id="variantPriceText">Rp -</div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs uppercase tracking-wide text-gray-400">Stok</div>
                            <div class="text-lg font-bold text-gray-800" id="variantStockText">-</div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah</label>
                        <input type="number" name="quantity" value="1" min="1" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50 text-center" required>
                    </div>

                    <button type="submit" id="addVariantButton" class="w-full text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:outline-none focus:ring-emerald-300 font-bold rounded-lg text-lg px-5 py-3 text-center transition-all shadow-lg hover:shadow-xl" disabled>
                        + Tambah ke Keranjang
                    </button>
                </form>
            </div>
        </div>

        <script>
            const variantsData = @json($variants);
            const option1Name = @json($option1Name);
            const option2Name = @json($option2Name);
            const hasOption2 = @json($hasOption2);

            const modal = document.getElementById('variantModal');
            const openBtn = document.getElementById('openVariantModal');
            const closeBtn = document.getElementById('closeVariantModal');
            const option1Select = document.getElementById('variantOption1');
            const option2Select = document.getElementById('variantOption2');
            const variantIdInput = document.getElementById('variantIdInput');
            const priceText = document.getElementById('variantPriceText');
            const stockText = document.getElementById('variantStockText');
            const addButton = document.getElementById('addVariantButton');
            const qtyInput = document.querySelector('#variantModal input[name="quantity"]');

            function formatRupiah(amount) {
                return 'Rp ' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            function resetSelection() {
                variantIdInput.value = '';
                priceText.textContent = 'Rp -';
                stockText.textContent = '-';
                addButton.disabled = true;
                if (qtyInput) {
                    qtyInput.removeAttribute('max');
                }
            }

            function populateOption1() {
                const values = [...new Set(variantsData.map(v => v.option1_value).filter(Boolean))];
                values.forEach((value) => {
                    const opt = document.createElement('option');
                    opt.value = value;
                    opt.textContent = value;
                    option1Select.appendChild(opt);
                });
            }

            function populateOption2(option1Value) {
                if (!hasOption2 || !option2Select) return;
                option2Select.innerHTML = `<option value="">-- Pilih ${option2Name} --</option>`;
                const values = variantsData
                    .filter(v => v.option1_value === option1Value)
                    .map(v => v.option2_value)
                    .filter(Boolean);
                [...new Set(values)].forEach((value) => {
                    const opt = document.createElement('option');
                    opt.value = value;
                    opt.textContent = value;
                    option2Select.appendChild(opt);
                });
                option2Select.disabled = values.length === 0;
            }

            function updateVariantSelection() {
                const opt1 = option1Select.value;
                const opt2 = option2Select ? option2Select.value : null;
                let matched = null;

                if (opt1) {
                    matched = variantsData.find(v => v.option1_value === opt1 && (!hasOption2 || v.option2_value === opt2));
                }

                if (matched) {
                    variantIdInput.value = matched.id;
                    priceText.textContent = formatRupiah(Math.round(matched.display_price));
                    stockText.textContent = matched.stock;
                    addButton.disabled = matched.stock < 1;
                    if (qtyInput) {
                        qtyInput.max = matched.stock;
                        if (parseInt(qtyInput.value || '1', 10) > matched.stock) {
                            qtyInput.value = matched.stock > 0 ? matched.stock : 1;
                        }
                    }
                } else {
                    resetSelection();
                }
            }

            if (openBtn) {
                openBtn.addEventListener('click', () => {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                });
            }

            if (modal) {
                modal.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    }
                });
            }

            populateOption1();

            option1Select.addEventListener('change', () => {
                resetSelection();
                if (hasOption2) {
                    populateOption2(option1Select.value);
                }
                updateVariantSelection();
            });

            if (option2Select) {
                option2Select.addEventListener('change', updateVariantSelection);
            }
        </script>
    @endif
</x-app-layout>
