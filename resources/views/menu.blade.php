@extends('layouts.app')

@section('content')
    {{-- Toast Notification (top right) --}}
    @if(session('success') === 'Produk berhasil ditambahkan ke keranjang!')
    <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" x-init="setTimeout(() => show = false, 2500)" class="fixed top-6 right-6 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2">
        <svg class="h-6 w-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
        <span class="font-semibold">{{ session('success') }}</span>
    </div>
    @endif
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold text-red-500 mb-8 text-center">Menu Kami</h1>

        {{-- Filter Kategori --}}
        <div class="mb-8 bg-white p-4 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Pilih Kategori:</h2>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('menu.index') }}"
                   class="px-5 py-2 rounded-full text-sm font-medium transition-colors
                          {{ !request()->has('category') ? 'bg-red-600 text-white' : 'bg-gray-200 hover:bg-red-500 text-gray-800' }}">
                    Semua
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('menu.index', ['category' => $category->id]) }}"
                       class="px-5 py-2 rounded-full text-sm font-medium transition-colors
                              {{ request()->input('category') == $category->id ? 'bg-red-600 text-white' : 'bg-gray-200 hover:bg-red-500 text-gray-800' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Pesan Ketersediaan Pengantaran --}}
        <div id="delivery-status-message" class="mb-8 p-4 rounded-lg text-center font-semibold">
            <!-- Pesan akan dimuat oleh JavaScript -->
            <p class="text-gray-600">Mengecek ketersediaan pengantaran...</p>
        </div>

        {{-- Daftar Produk --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($products as $product)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col transform hover:scale-105 transition-transform duration-300">
                    <a href="{{ route('menu.show', $product->id) }}" class="block">
                        @if($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover object-center">
                        @else
                            <img src="{{ asset('images/pizza-boxx-logo.png') }}" alt="Default Image" class="w-full h-48 object-cover object-center">
                        @endif
                    </a>
                    <div class="p-5 flex flex-col flex-grow">
                        <a href="{{ route('menu.show', $product->id) }}" class="block text-gray-800 hover:text-red-600 transition-colors">
                            <h3 class="text-2xl font-semibold mb-2">{{ $product->name }}</h3>
                        </a>
                        <p class="text-red-600 text-lg font-bold mb-3">Rp {{ number_format($product->base_price, 0, ',', '.') }}</p>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3 flex-grow">{{ $product->description ?? 'Tidak ada deskripsi.' }}</p>

                        @if($product->is_available)
                            @guest
                                <a href="{{ route('login') }}" class="w-full block bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full text-center transition-colors mt-auto">Pesan Sekarang</a>
                            @else
                                <form action="{{ route('cart.add') }}" method="POST" class="mt-auto add-to-cart-form" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}" data-base-price="{{ $product->base_price }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="product_name" value="{{ $product->name }}">
                                    <input type="hidden" name="quantity" value="1" class="quantity-input">

                                    {{-- Quantity Controls --}}
                                    <div class="flex items-center justify-between bg-gray-100 p-2 rounded-lg mb-3">
                                        <button type="button" class="decrease-quantity bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-3 rounded-full text-lg transition-colors">-</button>
                                        <span class="quantity-display text-xl font-bold text-gray-800">1</span>
                                        <button type="button" class="increase-quantity bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-3 rounded-full text-lg transition-colors">+</button>
                                    </div>

                                    {{-- Add to Cart Button (initially disabled) --}}
                                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full transition-colors order-button" disabled>
                                        Pesan Sekarang
                                    </button>
                                </form>
                            @endguest
                        @else
                            <button class="w-full bg-gray-400 text-gray-600 font-bold py-2 px-4 rounded-full cursor-not-allowed mt-auto" disabled>
                                Tidak Tersedia
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-gray-600 text-center col-span-full">Tidak ada produk yang tersedia dalam kategori ini.</p>
            @endforelse
        </div>
    </div>

    <script>
        // Data lokasi cabang dari PHP
        const branchLocationData = @json($branchLocationData);
        const deliveryStatusMessage = document.getElementById('delivery-status-message');
        const orderButtons = document.querySelectorAll('.order-button');
        const cartLink = document.querySelector('a[href="{{ route('cart.index') }}"]');

        // Fungsi Haversine untuk menghitung jarak antara dua titik Lat/Long
        function haversineDistance(lat1, lon1, lat2, lon2) {
            const R = 6371; // Radius Bumi dalam kilometer
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a =
                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * Math.sin(dLon / 2) * Math.sin(dLon / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            const distance = R * c; // Jarak dalam kilometer
            return distance;
        }

        function updateOrderButtonStatus(canOrder) {
            orderButtons.forEach(button => {
                if (canOrder) {
                    button.removeAttribute('disabled');
                    button.classList.remove('bg-gray-400', 'text-gray-600', 'cursor-not-allowed');
                    button.classList.add('bg-red-600', 'hover:bg-red-700', 'text-white');
                    button.textContent = 'Pesan Sekarang';
                } else {
                    button.setAttribute('disabled', 'disabled');
                    button.classList.remove('bg-red-600', 'hover:bg-red-700', 'text-white');
                    button.classList.add('bg-gray-400', 'text-gray-600', 'cursor-not-allowed');
                    button.textContent = 'Di Luar Jangkauan';
                }
            });
            // Juga nonaktifkan link keranjang jika tidak bisa memesan
            if (cartLink) {
                if (canOrder) {
                    cartLink.classList.remove('cursor-not-allowed', 'opacity-50');
                    cartLink.style.pointerEvents = 'auto'; // Re-enable click
                } else {
                    cartLink.classList.add('cursor-not-allowed', 'opacity-50');
                    cartLink.style.pointerEvents = 'none'; // Disable click
                }
            }
        }

        function checkDeliveryAvailability() {
            if (!branchLocationData) {
                deliveryStatusMessage.innerHTML = '<p class="text-red-500">Informasi lokasi cabang tidak lengkap di admin.</p>';
                deliveryStatusMessage.classList.add('bg-red-100', 'border', 'border-red-500');
                updateOrderButtonStatus(false);
                return;
            }

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const userLat = position.coords.latitude;
                        const userLon = position.coords.longitude;

                        const distance = haversineDistance(
                            branchLocationData.latitude,
                            branchLocationData.longitude,
                            userLat,
                            userLon
                        );

                        if (distance <= branchLocationData.radius_km) {
                            deliveryStatusMessage.innerHTML = `<p class="text-green-700">Anda berada dalam jangkauan pengantaran (${distance.toFixed(2)} km).</p>`;
                            deliveryStatusMessage.classList.remove('bg-red-100', 'border-red-500');
                            deliveryStatusMessage.classList.add('bg-green-100', 'border', 'border-green-700');
                            updateOrderButtonStatus(true);
                        } else {
                            deliveryStatusMessage.innerHTML = `<p class="text-red-700">Anda berada di luar jangkauan pengantaran (${distance.toFixed(2)} km). Tidak bisa memesan.</p>`;
                            deliveryStatusMessage.classList.remove('bg-green-100', 'border-green-700');
                            deliveryStatusMessage.classList.add('bg-red-100', 'border', 'border-red-700');
                            updateOrderButtonStatus(false);
                        }
                    },
                    (error) => {
                        console.error("Error getting location:", error);
                        let errorMessage = "Tidak dapat mendapatkan lokasi Anda. Pastikan layanan lokasi aktif.";
                        if (error.code === error.PERMISSION_DENIED) {
                            errorMessage = "Akses lokasi ditolak. Mohon izinkan akses lokasi untuk memesan.";
                        }
                        deliveryStatusMessage.innerHTML = `<p class="text-red-700">${errorMessage}</p>`;
                        deliveryStatusMessage.classList.remove('bg-green-100', 'border-green-700');
                        deliveryStatusMessage.classList.add('bg-red-100', 'border', 'border-red-700');
                        updateOrderButtonStatus(false);
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                );
            } else {
                deliveryStatusMessage.innerHTML = '<p class="text-red-700">Geolocation tidak didukung oleh browser Anda.</p>';
                deliveryStatusMessage.classList.remove('bg-green-100', 'border-green-700');
                deliveryStatusMessage.classList.add('bg-red-100', 'border', 'border-red-700');
                updateOrderButtonStatus(false);
            }
        }

        // JavaScript untuk kuantitas di halaman menu (sama seperti di product-detail)
        document.querySelectorAll('.add-to-cart-form').forEach(form => {
            const quantityDisplay = form.querySelector('.quantity-display');
            const quantityInput = form.querySelector('.quantity-input');
            const decreaseButton = form.querySelector('.decrease-quantity');
            const increaseButton = form.querySelector('.increase-quantity');

            let currentQuantity = parseInt(quantityInput.value);

            function updateQuantityDisplay() {
                quantityDisplay.textContent = currentQuantity;
                quantityInput.value = currentQuantity;
            }

            decreaseButton.addEventListener('click', (e) => {
                e.preventDefault();
                if (currentQuantity > 1) {
                    currentQuantity--;
                    updateQuantityDisplay();
                }
            });

            increaseButton.addEventListener('click', (e) => {
                e.preventDefault();
                currentQuantity++;
                updateQuantityDisplay();
            });
        });

        // Panggil fungsi cek ketersediaan saat halaman dimuat
        document.addEventListener('DOMContentLoaded', checkDeliveryAvailability);
    </script>
@endsection
