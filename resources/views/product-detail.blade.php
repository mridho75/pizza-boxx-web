@extends('layouts.app')

@section('content')
    {{-- Toast Notification --}}
    <div id="toast" class="fixed top-6 right-6 z-50 hidden min-w-[220px] max-w-xs bg-green-600 text-white px-5 py-3 rounded-lg shadow-lg flex items-center gap-2 animate-slide-in-down" role="alert" aria-live="polite">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        <span id="toast-message">Berhasil ditambahkan ke keranjang!</span>
    </div>
    <div class="container mx-auto px-2 py-6 md:px-4 md:py-10 animate-fade-in">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row transition-all duration-500">
            {{-- Product Image --}}
            <div class="md:w-1/2 flex items-center justify-center bg-gradient-to-br from-red-50 to-yellow-50 p-4 md:p-8">
                @if($product->image_path)
                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full max-w-md h-80 md:h-96 object-cover object-center rounded-xl shadow-lg transform transition-transform duration-500 hover:scale-105 hover:shadow-2xl" loading="lazy">
                @else
                    <img src="{{ asset('images/pizza-boxx-logo.png') }}" alt="Default Image" class="w-full max-w-md h-80 md:h-96 object-cover object-center rounded-xl shadow-lg">
                @endif
            </div>

            {{-- Product Details and Options Form --}}
            <div class="md:w-1/2 p-6 md:p-10 text-gray-800 flex flex-col justify-center">
                <h1 class="text-3xl md:text-5xl font-extrabold text-red-600 mb-3 md:mb-5 tracking-tight animate-slide-in">{{ $product->name }}</h1>
                <p class="text-gray-600 text-base md:text-lg mb-4 md:mb-8 leading-relaxed">{{ $product->description ?? 'Tidak ada deskripsi.' }}</p>

                <form id="addToCartForm" action="{{ route('cart.add') }}" method="POST" data-product-base-price="{{ $product->base_price }}" autocomplete="off">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="product_name" value="{{ $product->name }}">

                    @php
                        // Cek apakah ada opsi ukuran untuk produk ini
                        $hasSizeOptions = $product->options->where('type', 'Ukuran')->isNotEmpty();
                        // Cek apakah produk ini adalah kategori Pizza
                        $isPizzaCategory = ($product->category->name === 'Pizza');
                    @endphp

                    @if($hasSizeOptions)
                        <div class="mb-4 md:mb-6">
                            <label for="size_option" class="block text-lg md:text-xl font-semibold mb-2 md:mb-3">Pilih Ukuran:</label>
                            <select id="size_option" name="size_option_id" required
                                class="w-full p-3 rounded-xl bg-gray-100 border border-gray-300 text-gray-800 focus:ring-2 focus:ring-red-400 focus:border-red-500 transition-all duration-300 shadow-sm">
                                <option value="" data-size-suffix="">Pilih Ukuran</option>
                                @foreach($product->options->where('type', 'Ukuran') as $option)
                                    @php
                                        $suffix = '';
                                        if (str_contains(strtolower($option->name), 'personal')) { $suffix = 'P'; }
                                        elseif (str_contains(strtolower($option->name), 'reguler')) { $suffix = 'R'; }
                                        elseif (str_contains(strtolower($option->name), 'large')) { $suffix = 'L'; }
                                    @endphp
                                    <option value="{{ $option->id }}" data-price-modifier="{{ $option->price_modifier }}" data-size-suffix="{{ $suffix }}">
                                        {{ $option->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        {{-- Input hidden untuk size jika tidak ada opsi ukuran --}}
                        <input type="hidden" name="size_option_id" value="">
                    @endif

                    @if($isPizzaCategory)
                        <div class="mb-6">
                            <label for="crust_option" class="block text-xl font-semibold mb-3">Pilih Pinggiran:</label>
                            <select id="crust_option" name="crust_option_id" required
                                class="w-full p-3 rounded-lg bg-gray-100 border border-gray-300 text-gray-800 focus:ring-red-500 focus:border-red-500 transition-colors"> {{-- Perubahan: bg-gray-100, border-gray-300, text-gray-800 --}}
                                <option value="">Pilih Pinggiran</option>
                                {{-- Data opsi pinggiran akan ditambahkan oleh JS di sini --}}
                            </select>
                        </div>
                    @else
                        {{-- Input hidden untuk crust jika bukan kategori Pizza --}}
                        <input type="hidden" name="crust_option_id" value="">
                    @endif

                    <div class="mb-4 md:mb-6">
                        <label class="block text-lg md:text-xl font-semibold mb-2 md:mb-3">Pilih Tambahan (Add-ons):</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                            @forelse($product->addons as $addon)
                                <label class="flex items-center p-3 bg-gray-100 rounded-xl cursor-pointer hover:bg-yellow-100 transition-all duration-300 shadow-sm group">
                                    <input type="checkbox" name="addons[]" value="{{ $addon->id }}" data-price="{{ $addon->price }}"
                                        class="form-checkbox h-5 w-5 text-red-600 rounded focus:ring-red-500 transition-all duration-200">
                                    <span class="ml-3 text-base md:text-lg text-gray-800 group-hover:text-red-600 transition-colors">{{ $addon->name }}</span>
                                    <span class="ml-auto text-red-600 font-semibold text-sm md:text-base">Rp {{ number_format($addon->price, 0, ',', '.') }}</span>
                                </label>
                            @empty
                                <p class="text-gray-600 text-sm col-span-full">Tidak ada tambahan tersedia untuk produk ini.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="mb-4 md:mb-6 flex flex-col md:flex-row items-center justify-between bg-gray-100 p-4 rounded-xl shadow-sm gap-3 md:gap-0">
                        <div class="flex items-center space-x-3 md:space-x-4">
                            <button type="button" id="decreaseQuantity" aria-label="Kurangi jumlah" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full text-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-400">-</button>
                            <span id="quantityDisplay" class="text-2xl font-bold text-gray-800 select-none">1</span>
                            <input type="hidden" name="quantity" id="quantityInput" value="1">
                            <button type="button" id="increaseQuantity" aria-label="Tambah jumlah" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full text-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-400">+</button>
                        </div>
                        <div class="text-right w-full md:w-auto">
                            <span class="text-base md:text-lg text-gray-600">Total:</span>
                            <p id="totalPrice" class="text-3xl font-extrabold text-red-600 transition-all duration-300">Rp {{ number_format($product->base_price, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-red-600 to-yellow-500 hover:from-red-700 hover:to-yellow-600 text-white font-extrabold py-3 px-6 rounded-full text-xl shadow-lg transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-400 animate-bounce-once">
                        <span class="inline-block align-middle"><svg class="inline w-6 h-6 mr-2 -mt-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m5-9v9m4-9v9m1-9h2a2 2 0 002-2V7a2 2 0 00-2-2h-2.4"></path></svg></span>Tambah ke Keranjang
                    </button>
                </form>
            </div>
        </div>
    </div>

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fade-in 0.7s cubic-bezier(.4,0,.2,1) both; }
        @keyframes slide-in {
            from { opacity: 0; transform: translateX(-40px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-slide-in { animation: slide-in 0.7s cubic-bezier(.4,0,.2,1) both; }
        @keyframes bounce-once {
            0% { transform: scale(1); }
            30% { transform: scale(1.12); }
            60% { transform: scale(0.96); }
            100% { transform: scale(1); }
        }
        .animate-bounce-once { animation: bounce-once 0.6s 1; }
    </style>
    <style>
        @keyframes slide-in-down {
            from { opacity: 0; transform: translateY(-40px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-slide-in-down { animation: slide-in-down 0.5s cubic-bezier(.4,0,.2,1) both; }
    </style>
    <script>
        const addToCartForm = document.getElementById('addToCartForm');
        const basePrice = parseFloat(addToCartForm.getAttribute('data-product-base-price'));

        const allUniversalCrusts = @json($universalCrusts);

        const sizeSelect = document.getElementById('size_option');
        const crustSelect = document.getElementById('crust_option');
        const addonCheckboxes = document.querySelectorAll('input[name="addons[]"]');

        const quantityDisplay = document.getElementById('quantityDisplay');
        const quantityInput = document.getElementById('quantityInput');
        const increaseQuantityBtn = document.getElementById('increaseQuantity');
        const decreaseQuantityBtn = document.getElementById('decreaseQuantity');
        const totalPriceDisplay = document.getElementById('totalPrice');

        let currentQuantity = parseInt(quantityInput.value);

        function updateCrustOptions() {
            if (!sizeSelect || !crustSelect) return;

            const selectedSizeOption = sizeSelect.options[sizeSelect.selectedIndex];
            const selectedSizeSuffix = selectedSizeOption.getAttribute('data-size-suffix');

            const currentSelectedCrustValue = crustSelect.value;
            crustSelect.innerHTML = '<option value="">Pilih Pinggiran</option>';

            const relevantCrusts = allUniversalCrusts.filter(crust => {
                const crustName = crust.name.toLowerCase();

                if (crustName.includes('original')) {
                    return true;
                }
                const match = crustName.match(/\((\w)\)$/);
                const crustSuffix = match ? match[1].toUpperCase() : '';

                return crustSuffix === selectedSizeSuffix;
            });

            relevantCrusts.forEach(crust => {
                const option = document.createElement('option');
                option.value = crust.id;
                option.setAttribute('data-price-modifier', crust.price_modifier);
                let displayCrustName = crust.name.replace(/\s*\([PRL]\)$/i, '');
                option.textContent = displayCrustName;
                crustSelect.appendChild(option);
            });

            if (relevantCrusts.some(crust => crust.id == currentSelectedCrustValue)) {
                crustSelect.value = currentSelectedCrustValue;
            } else {
                crustSelect.value = "";
            }
        }

        function updateTotalPrice() {
            let currentPrice = basePrice;
            if (sizeSelect && sizeSelect.options[sizeSelect.selectedIndex] && sizeSelect.options[sizeSelect.selectedIndex].value) {
                currentPrice += parseFloat(sizeSelect.options[sizeSelect.selectedIndex].getAttribute('data-price-modifier'));
            }
            if (crustSelect && crustSelect.options[crustSelect.selectedIndex] && crustSelect.options[crustSelect.selectedIndex].value) {
                currentPrice += parseFloat(crustSelect.options[crustSelect.selectedIndex].getAttribute('data-price-modifier'));
            }
            addonCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    currentPrice += parseFloat(checkbox.getAttribute('data-price'));
                }
            });
            currentPrice *= currentQuantity;
            // Animasi transisi harga
            if (totalPriceDisplay.dataset.lastValue) {
                totalPriceDisplay.classList.remove('animate-pulse');
                void totalPriceDisplay.offsetWidth;
                totalPriceDisplay.classList.add('animate-pulse');
            }
            totalPriceDisplay.textContent = `Rp ${currentPrice.toLocaleString('id-ID')}`;
            totalPriceDisplay.dataset.lastValue = currentPrice;
        }
        // Tambah animasi pulse pada harga
        const style = document.createElement('style');
        style.innerHTML = `@keyframes pulse { 0%{background:transparent;} 50%{background:rgba(255,230,230,0.7);} 100%{background:transparent;} } .animate-pulse{animation:pulse 0.5s;}`;
        document.head.appendChild(style);

        if (sizeSelect) {
            sizeSelect.addEventListener('change', () => {
                updateCrustOptions();
                updateTotalPrice();
            });
        }
        if (crustSelect) {
            crustSelect.addEventListener('change', updateTotalPrice);
        }

        addonCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateTotalPrice);
        });

        increaseQuantityBtn.addEventListener('click', () => {
            currentQuantity++;
            quantityDisplay.textContent = currentQuantity;
            quantityInput.value = currentQuantity;
            updateTotalPrice();
        });

        decreaseQuantityBtn.addEventListener('click', () => {
            if (currentQuantity > 1) {
                currentQuantity--;
                quantityDisplay.textContent = currentQuantity;
                quantityInput.value = currentQuantity;
                updateTotalPrice();
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            if (sizeSelect && crustSelect) {
                updateCrustOptions();
            }
            updateTotalPrice();

            // Toast notification jika ada session flash (dari backend)
            @if(session('success'))
                showToast(@json(session('success')));
            @endif
        });

        // Toast notification logic
        function showToast(message) {
            const toast = document.getElementById('toast');
            const toastMsg = document.getElementById('toast-message');
            toastMsg.textContent = message || 'Berhasil ditambahkan ke keranjang!';
            toast.classList.remove('hidden');
            toast.classList.add('flex');
            setTimeout(() => {
                toast.classList.add('hidden');
                toast.classList.remove('flex');
            }, 2500);
        }

        // Intercept form submit for smooth UX (AJAX, optional fallback)
        addToCartForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(addToCartForm);
            fetch(addToCartForm.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': formData.get('_token'),
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success || data.status === 'success') {
                    showToast(data.message || 'Berhasil ditambahkan ke keranjang!');
                } else {
                    showToast(data.message || 'Gagal menambah ke keranjang');
                }
            })
            .catch(() => {
                showToast('Terjadi kesalahan.');
            });
        });
    </script>
@endsection
