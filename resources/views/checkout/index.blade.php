@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold text-red-500 mb-8 text-center">Lanjutkan ke Checkout</h1>

        @if(session('error'))
            <div class="bg-red-500 text-white p-3 rounded-lg mb-4 text-center">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="bg-green-500 text-white p-3 rounded-lg mb-4 text-center">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-500 text-white p-3 rounded-lg mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white p-6 rounded-lg shadow-lg">
            <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
                @csrf

                {{-- Ringkasan Keranjang --}}
                <h2 class="text-2xl font-bold text-red-600 mb-4">Ringkasan Pesanan Anda</h2>
                <div class="overflow-x-auto mb-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Produk</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Kuantitas</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($cart as $itemKey => $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                @if($item['image_path'])
                                                    <img class="h-8 w-8 rounded-full object-cover" src="{{ asset('storage/' . $item['image_path']) }}" alt="{{ $item['name'] }}">
                                                @else
                                                    <img class="h-8 w-8 rounded-full object-cover" src="{{ asset('images/pizza-boxx-logo.png') }}" alt="Default Image">
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-800">{{ $item['name'] }}</div>
                                                @if($item['size_option'])
                                                    <div class="text-xs text-gray-500">- Ukuran: {{ $item['size_option']['name'] }}</div>
                                                @endif
                                                @if($item['crust_option'])
                                                    <div class="text-xs text-gray-500">- Pinggiran: {{ $item['crust_option']['name'] }}</div>
                                                @endif
                                                @if(!empty($item['addons']))
                                                    <div class="text-xs text-gray-500">- Tambahan:
                                                        @foreach($item['addons'] as $addon)
                                                            {{ $addon['name'] }}@if(!$loop->last), @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item['quantity'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-medium">Rp {{ number_format($item['total_price'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Detail Pelanggan --}}
                <h2 class="text-2xl font-bold text-red-600 mb-4">Detail Pelanggan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="customer_name" class="block text-lg font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" id="customer_name" name="customer_name" required
                            class="w-full p-3 rounded-lg bg-gray-100 border border-gray-300 text-gray-800 focus:ring-red-500 focus:border-red-500"
                            value="{{ old('customer_name', $user->name ?? '') }}">
                    </div>
                    <div>
                        <label for="customer_email" class="block text-lg font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="customer_email" name="customer_email"
                            class="w-full p-3 rounded-lg bg-gray-100 border border-gray-300 text-gray-800 focus:ring-red-500 focus:border-red-500"
                            value="{{ old('customer_email', $user->email ?? '') }}">
                    </div>
                    <div>
                        <label for="customer_phone" class="block text-lg font-medium text-gray-700 mb-2">Nomor Telepon <span class="text-red-500">*</span></label>
                        <input type="tel" id="customer_phone" name="customer_phone" required
                            class="w-full p-3 rounded-lg bg-gray-100 border border-gray-300 text-gray-800 focus:ring-red-500 focus:border-red-500"
                            value="{{ old('customer_phone', $user->phone_number ?? '') }}">
                    </div>
                    <div>
                        <label for="location_id" class="block text-lg font-medium text-gray-700 mb-2">Pilih Lokasi Toko <span class="text-red-500">*</span></label>
                        <select id="location_id" name="location_id" required
                            class="w-full p-3 rounded-lg bg-gray-100 border border-gray-300 text-gray-800 focus:ring-red-500 focus:border-red-500">
                            <option value="">Pilih Lokasi</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                    {{ $location->name }} ({{ $location->address }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Tipe Pesanan --}}
                <h2 class="text-2xl font-bold text-red-600 mb-4">Tipe Pesanan</h2>
                <div class="mb-6 flex space-x-4">
                    <label class="flex items-center">
                        <input type="radio" name="order_type" value="delivery" id="order_type_delivery" class="form-radio h-5 w-5 text-red-600" checked>
                        <span class="ml-2 text-lg text-gray-800">Delivery</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="order_type" value="pickup" id="order_type_pickup" class="form-radio h-5 w-5 text-red-600">
                        <span class="ml-2 text-lg text-gray-800">Pickup</span>
                    </label>
                </div>

                {{-- Delivery Details (Muncul jika order_type = delivery) --}}
                <div id="delivery_details" class="mb-6">
                    <h3 class="text-xl font-bold text-gray-700 mb-3">Detail Pengiriman</h3>
                    <div class="mb-4">
                        <label for="delivery_address" class="block text-lg font-medium text-gray-700 mb-2">Alamat Pengiriman <span class="text-red-500">*</span></label>
                        <textarea id="delivery_address" name="delivery_address" rows="3"
                            class="w-full p-3 rounded-lg bg-gray-100 border border-gray-300 text-gray-800 focus:ring-red-500 focus:border-red-500">{{ old('delivery_address', $user->address ?? '') }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label for="delivery_notes" class="block text-lg font-medium text-gray-700 mb-2">Catatan Pengiriman (opsional)</label>
                        <textarea id="delivery_notes" name="delivery_notes" rows="2"
                            class="w-full p-3 rounded-lg bg-gray-100 border border-gray-300 text-gray-800 focus:ring-red-500 focus:border-red-500">{{ old('delivery_notes') }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label for="delivery_fee_display" class="block text-lg font-medium text-gray-700 mb-2">Biaya Pengiriman</label>
                        <p id="delivery_fee_display" class="text-xl font-semibold text-red-600">Rp 0</p>
                        <input type="hidden" name="delivery_fee" id="delivery_fee_input" value="0">
                    </div>
                </div>

                {{-- Promo Code --}}
                <h2 class="text-2xl font-bold text-red-600 mb-4">Kode Promo</h2>
                <div class="mb-6 flex">
                    <input type="text" id="promo_code" name="promo_code" placeholder="Masukkan kode promo Anda"
                        class="flex-grow p-3 rounded-l-lg bg-gray-100 border border-gray-300 text-gray-800 focus:ring-red-500 focus:border-red-500"
                        value="{{ old('promo_code') }}">
                    <button type="button" id="apply_promo_btn" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-r-lg transition-colors">
                        Terapkan
                    </button>
                </div>
                <div id="promo_message" class="text-sm mb-4"></div>
                <input type="hidden" name="discount_amount" id="discount_amount_input" value="0">

                {{-- Metode Pembayaran --}}
                <h2 class="text-2xl font-bold text-red-600 mb-4">Metode Pembayaran</h2>
                <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="flex items-center p-4 bg-gray-100 rounded-lg cursor-pointer hover:bg-gray-200 transition-colors">
                        <input type="radio" name="payment_method" value="cash_on_delivery" id="payment_method_cod" class="form-radio h-5 w-5 text-red-600" checked>
                        <span class="ml-3 text-lg text-gray-800">Cash On Delivery (COD)</span>
                    </label>
                    <label class="flex items-center p-4 bg-gray-100 rounded-lg cursor-pointer hover:bg-gray-200 transition-colors">
                        <input type="radio" name="payment_method" value="card_on_pickup" id="payment_method_cop" class="form-radio h-5 w-5 text-red-600">
                        <span class="ml-3 text-lg text-gray-800">Card On Pickup (Bayar di Toko)</span>
                    </label>
                    <label class="flex items-center p-4 bg-gray-100 rounded-lg cursor-pointer hover:bg-gray-200 transition-colors">
                        <input type="radio" name="payment_method" value="online" id="payment_method_online" class="form-radio h-5 w-5 text-red-600">
                        <span class="ml-3 text-lg text-gray-800">Pembayaran Online (Coming Soon)</span>
                    </label>
                </div>

                {{-- Ringkasan Total --}}
                <div class="mt-8 border-t border-gray-200 pt-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-lg text-gray-700">Subtotal:</span>
                        <span id="subtotal_display" class="text-xl font-semibold text-gray-800">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                        <input type="hidden" name="subtotal_amount" id="subtotal_amount_input" value="{{ $cartTotal }}">
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-lg text-gray-700">Diskon Promo:</span>
                        <span id="discount_display" class="text-xl font-semibold text-green-600">- Rp 0</span>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-lg text-gray-700">Biaya Pengiriman:</span>
                        <span id="delivery_fee_summary_display" class="text-xl font-semibold text-gray-800">Rp 0</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-2xl font-bold text-gray-800">Total Pembayaran:</span>
                        <span id="final_total_display" class="text-4xl font-extrabold text-red-600">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                        <input type="hidden" name="total_amount" id="total_amount_input" value="{{ $cartTotal }}">
                    </div>
                </div>

                <div class="mt-8">
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 px-6 rounded-full text-2xl transition-colors">
                        Konfirmasi Pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const orderTypeDelivery = document.getElementById('order_type_delivery');
            const orderTypePickup = document.getElementById('order_type_pickup');
            const deliveryDetails = document.getElementById('delivery_details');
            const deliveryAddressInput = document.getElementById('delivery_address');
            const paymentMethodCod = document.getElementById('payment_method_cod');
            const paymentMethodCop = document.getElementById('payment_method_cop');
            const paymentMethodOnline = document.getElementById('payment_method_online');
            const deliveryFeeDisplay = document.getElementById('delivery_fee_display');
            const deliveryFeeInput = document.getElementById('delivery_fee_input');
            const deliveryFeeSummaryDisplay = document.getElementById('delivery_fee_summary_display');

            const subtotalAmount = parseFloat(document.getElementById('subtotal_amount_input').value);
            const discountAmountInput = document.getElementById('discount_amount_input');
            const discountDisplay = document.getElementById('discount_display');
            const finalTotalDisplay = document.getElementById('final_total_display');
            const totalAmountInput = document.getElementById('total_amount_input');
            const promoCodeInput = document.getElementById('promo_code');
            const applyPromoBtn = document.getElementById('apply_promo_btn');
            const promoMessage = document.getElementById('promo_message');

            // Initial state
            function updateOrderTypeVisibility() {
                if (orderTypeDelivery.checked) {
                    deliveryDetails.style.display = 'block';
                    deliveryAddressInput.setAttribute('required', 'required');
                    paymentMethodCod.checked = true;
                    paymentMethodCop.disabled = true;
                    paymentMethodCop.checked = false;
                    paymentMethodOnline.disabled = false;
                    updateDeliveryFee(5000);
                } else { // pickup
                    deliveryDetails.style.display = 'none';
                    deliveryAddressInput.removeAttribute('required');
                    deliveryAddressInput.value = '';
                    paymentMethodCop.checked = true;
                    paymentMethodCop.disabled = false;
                    paymentMethodCod.disabled = true;
                    paymentMethodOnline.disabled = false;
                    updateDeliveryFee(0);
                }
                updateFinalTotal();
            }

            function updateDeliveryFee(fee) {
                deliveryFeeInput.value = fee;
                deliveryFeeDisplay.textContent = `Rp ${fee.toLocaleString('id-ID')}`;
                deliveryFeeSummaryDisplay.textContent = `Rp ${fee.toLocaleString('id-ID')}`;
            }

            function updateFinalTotal() {
                let currentDiscount = parseFloat(discountAmountInput.value);
                let currentDeliveryFee = parseFloat(deliveryFeeInput.value);
                let finalTotal = subtotalAmount - currentDiscount + currentDeliveryFee;
                finalTotalDisplay.textContent = `Rp ${finalTotal.toLocaleString('id-ID')}`;
                totalAmountInput.value = finalTotal;
            }

            // Event Listeners
            orderTypeDelivery.addEventListener('change', updateOrderTypeVisibility);
            orderTypePickup.addEventListener('change', updateOrderTypeVisibility);

            applyPromoBtn.addEventListener('click', function() {
                const promoCode = promoCodeInput.value;
                if (!promoCode) {
                    promoMessage.textContent = 'Masukkan kode promo.';
                    promoMessage.className = 'text-sm mb-4 text-yellow-600';
                    discountAmountInput.value = 0;
                    discountDisplay.textContent = `- Rp 0`;
                    updateFinalTotal();
                    return;
                }

                fetch('/checkout/api/validate-promo', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ promo_code: promoCode, subtotal: subtotalAmount })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        discountAmountInput.value = data.discount_amount;
                        discountDisplay.textContent = `- Rp ${data.discount_amount.toLocaleString('id-ID')}`;
                        promoMessage.textContent = `Promo berhasil diterapkan! Anda menghemat Rp ${data.discount_amount.toLocaleString('id-ID')}.`;
                        promoMessage.className = 'text-sm mb-4 text-green-600';
                    } else {
                        discountAmountInput.value = 0;
                        discountDisplay.textContent = `- Rp 0`;
                        promoMessage.textContent = data.message;
                        promoMessage.className = 'text-sm mb-4 text-red-600';
                    }
                    updateFinalTotal();
                })
                .catch(error => {
                    console.error('Error:', error);
                    promoMessage.textContent = 'Terjadi kesalahan saat memeriksa promo.';
                    promoMessage.className = 'text-sm mb-4 text-red-600';
                    discountAmountInput.value = 0;
                    discountDisplay.textContent = `- Rp 0`;
                    updateFinalTotal();
                });
            });

            paymentMethodOnline.addEventListener('change', function() {
                if (this.checked) {
                    alert('Pembayaran online akan segera tersedia. Mohon pilih metode pembayaran lain untuk saat ini.');
                    if (orderTypeDelivery.checked) {
                        paymentMethodCod.checked = true;
                    } else {
                        paymentMethodCop.checked = true;
                    }
                }
            });

            updateOrderTypeVisibility();
        });
    </script>
@endsection
