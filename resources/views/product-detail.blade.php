@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden md:flex"> {{-- Perubahan: bg-white --}}
            {{-- Product Image --}}
            <div class="md:w-1/2">
                @if($product->image_path)
                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-96 object-cover object-center">
                @else
                    <img src="{{ asset('images/pizza-boxx-logo.png') }}" alt="Default Image" class="w-full h-96 object-cover object-center">
                @endif
            </div>

            {{-- Product Details and Options Form --}}
            <div class="md:w-1/2 p-8 text-gray-800"> {{-- Perubahan: text-gray-800 --}}
                <h1 class="text-4xl font-bold text-red-600 mb-4">{{ $product->name }}</h1> {{-- Perubahan: text-red-600 --}}
                <p class="text-gray-600 text-lg mb-6">{{ $product->description ?? 'Tidak ada deskripsi.' }}</p> {{-- Perubahan: text-gray-600 --}}

                <form id="addToCartForm" action="{{ route('cart.add') }}" method="POST" data-product-base-price="{{ $product->base_price }}">
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
                        <div class="mb-6">
                            <label for="size_option" class="block text-xl font-semibold mb-3">Pilih Ukuran:</label>
                            <select id="size_option" name="size_option_id" required
                                class="w-full p-3 rounded-lg bg-gray-100 border border-gray-300 text-gray-800 focus:ring-red-500 focus:border-red-500 transition-colors"> {{-- Perubahan: bg-gray-100, border-gray-300, text-gray-800 --}}
                                <option value="" data-size-suffix="">Pilih Ukuran</option>
                                @foreach($product->options->where('type', 'Ukuran') as $option)
                                    @php
                                        // Tentukan suffix berdasarkan nama ukuran
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

                    <div class="mb-6">
                        <label class="block text-xl font-semibold mb-3">Pilih Tambahan (Add-ons):</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($product->addons as $addon)
                                <label class="flex items-center p-3 bg-gray-100 rounded-lg cursor-pointer hover:bg-gray-200 transition-colors"> {{-- Perubahan: bg-gray-100, hover:bg-gray-200 --}}
                                    <input type="checkbox" name="addons[]" value="{{ $addon->id }}" data-price="{{ $addon->price }}"
                                        class="form-checkbox h-5 w-5 text-red-600 rounded focus:ring-red-500">
                                    <span class="ml-3 text-lg text-gray-800">{{ $addon->name }}</span> {{-- Perubahan: text-gray-800 --}}
                                    <span class="ml-auto text-red-600 font-semibold">Rp {{ number_format($addon->price, 0, ',', '.') }}</span> {{-- Perubahan: text-red-600 --}}
                                </label>
                            @empty
                                <p class="text-gray-600 text-sm col-span-full">Tidak ada tambahan tersedia untuk produk ini.</p> {{-- Perubahan: text-gray-600 --}}
                            @endforelse
                        </div>
                    </div>

                    <div class="mb-6 flex items-center justify-between bg-gray-100 p-4 rounded-lg"> {{-- Perubahan: bg-gray-100 --}}
                        <div class="flex items-center space-x-4">
                            <button type="button" id="decreaseQuantity" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full text-xl transition-colors">-</button>
                            <span id="quantityDisplay" class="text-2xl font-bold text-gray-800">1</span> {{-- Perubahan: text-gray-800 --}}
                            <input type="hidden" name="quantity" id="quantityInput" value="1">
                            <button type="button" id="increaseQuantity" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full text-xl transition-colors">+</button>
                        </div>
                        <div class="text-right">
                            <span class="text-lg text-gray-600">Total:</span> {{-- Perubahan: text-gray-600 --}}
                            <p id="totalPrice" class="text-3xl font-extrabold text-red-600">Rp {{ number_format($product->base_price, 0, ',', '.') }}</p> {{-- Perubahan: text-red-600 --}}
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-full text-xl transition-colors">
                        Tambah ke Keranjang
                    </button>
                </form>
            </div>
        </div>
    </div>

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

            totalPriceDisplay.textContent = `Rp ${currentPrice.toLocaleString('id-ID')}`;
        }

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
        });
    </script>
@endsection