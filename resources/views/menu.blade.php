@extends('layouts.app')

@section('content')
    {{-- Toast Notification (top right) for cart updates --}}
    @if(session('success') === 'Produk berhasil ditambahkan ke keranjang!')
    <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4" x-init="setTimeout(() => show = false, 3000)"
         class="fixed top-6 right-6 z-[100] bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-xl shadow-2xl flex items-center space-x-3 border-l-4 border-white/20 animate-pulse">
        <div class="bg-white/20 p-1 rounded-full">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <div>
            <p class="font-bold">Berhasil!</p>
            <p class="text-sm">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <div class="bg-gradient-to-b from-orange-50 to-white pb-16">
        <div class="container mx-auto px-4 py-12">
            <!-- Hero Header Section with Floating Animation -->
            <div class="text-center mb-16 animate-float">
                <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 mb-4">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-red-600 to-orange-500">
                        Menu Kami
                    </span>
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Temukan kelezatan yang memanjakan lidah Anda</p>

                <!-- Animated Scroll Down Indicator -->
                <div class="mt-8 animate-bounce">
                    <svg class="w-8 h-8 mx-auto text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                </div>
            </div>

            {{-- Filter Kategori with Sticky Behavior --}}
            <div class="sticky top-16 z-10 bg-gradient-to-b from-orange-50 to-transparent pt-4 pb-8 -mx-4 px-4">
                <div class="flex justify-center flex-wrap gap-3 mb-8">
                    <a href="{{ route('menu.index') }}"
                       class="px-6 py-2 text-sm font-bold transition-all duration-200 rounded-full shadow-md hover:shadow-lg
                              {{ !request()->has('category') ? 'bg-gradient-to-r from-red-600 to-orange-500 text-white shadow-lg scale-105' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                        Semua Menu
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('menu.index', ['category' => $category->id]) }}"
                           class="px-6 py-2 text-sm font-bold transition-all duration-200 rounded-full shadow-md hover:shadow-lg
                                  {{ request()->input('category') == $category->id ? 'bg-gradient-to-r from-red-600 to-orange-500 text-white shadow-lg scale-105' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Pesan Ketersediaan Pengantaran with Loading Animation --}}
            <div id="delivery-status-message"
                 class="max-w-3xl mx-auto bg-gradient-to-r from-blue-100 to-blue-50 border-l-4 border-blue-500 text-blue-800 p-4 rounded-r-lg mb-12 flex items-start gap-4 shadow-md transition-all duration-300 hover:shadow-lg">
                <svg class="w-6 h-6 flex-shrink-0 text-blue-500 mt-0.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <div>
                    <p class="text-sm font-medium"><span class="font-bold">Memeriksa lokasi Anda...</span> Mohon tunggu sebentar.</p>
                    <div class="w-full bg-blue-200 rounded-full h-1.5 mt-2">
                        <div class="bg-blue-600 h-1.5 rounded-full animate-pulse" style="width: 45%"></div>
                    </div>
                </div>
            </div>

            {{-- Daftar Produk with Grid Animation --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8" x-data="{
                observe() {
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                entry.target.classList.add('animate-fadeInUp')
                            }
                        })
                    }, { threshold: 0.1 })

                    document.querySelectorAll('.product-card').forEach(card => {
                        observer.observe(card)
                    })
                }
            }" x-init="observe()">
                @forelse($products as $product)
                    <div class="product-card opacity-0 transform translate-y-10 bg-white rounded-2xl shadow-lg overflow-hidden group hover:-translate-y-2 transition-all duration-500 flex flex-col border border-gray-100 hover:shadow-xl">
                        <!-- Product Image with Hover Effect -->
                        <div class="relative h-64 overflow-hidden">
                            @if($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif

                            <!-- Popular Badge -->
                            @if($product->is_popular)
                                <div class="absolute top-4 left-4 bg-gradient-to-r from-red-600 to-orange-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg animate-pulse">
                                    🔥 Terlaris
                                </div>
                            @endif

                            <!-- Price Tag -->
                            <div class="absolute bottom-4 right-4 bg-white/90 text-red-600 font-bold px-3 py-1 rounded-lg shadow-md backdrop-blur-sm transform transition-transform group-hover:scale-110">
                                Rp {{ number_format($product->base_price, 0, ',', '.') }}
                            </div>
                        </div>

                        <!-- Product Content -->
                        <div class="p-5 flex flex-col flex-grow">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-xl font-bold text-gray-800 group-hover:text-red-600 transition-colors">{{ $product->name }}</h3>
                                @if($product->is_available)
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded-full">Tersedia</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded-full">Habis</span>
                                @endif
                            </div>

                            <p class="text-gray-500 text-sm mb-4 flex-grow line-clamp-2">{{ $product->description ?? 'Deskripsi singkat tidak tersedia.' }}</p>

                            <!-- Rating Stars with Review Count -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="flex text-yellow-400 mr-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 @if($i <= ($product->rating ?? 4)) fill-current @else fill-none stroke-current stroke-1 @endif" viewBox="0 0 24 24">
                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-gray-500 text-xs">({{ $product->review_count ?? 24 }})</span>
                                </div>
                                @if($product->is_spicy)
                                    <span class="text-xs bg-orange-100 text-orange-800 px-2 py-1 rounded-full flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path>
                                        </svg>
                                        Pedas
                                    </span>
                                @endif
                            </div>

                            <!-- Order Button with Hover Effect -->
                            @if($product->is_available)
                                @guest
                                    <a href="{{ route('login') }}"
                                       class="mt-auto w-full bg-gradient-to-r from-red-600 to-orange-500 text-white font-bold py-3 rounded-lg hover:from-red-700 hover:to-orange-600 transition-all duration-300 flex items-center justify-center gap-2 shadow-md hover:shadow-lg group-hover:scale-[1.02]">
                                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4z"></path>
                                        </svg>
                                        Masuk untuk Memesan
                                    </a>
                                @else
                                    <a href="{{ route('menu.show', $product->id) }}"
                                       class="mt-auto w-full bg-gradient-to-r from-red-600 to-orange-500 text-white font-bold py-3 rounded-lg hover:from-red-700 hover:to-orange-600 transition-all duration-300 flex items-center justify-center gap-2 shadow-md hover:shadow-lg group-hover:scale-[1.02]">
                                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4z"></path>
                                        </svg>
                                        Pesan Sekarang
                                    </a>
                                @endguest
                            @else
                                <button class="mt-auto w-full bg-gray-100 text-gray-500 font-bold py-3 rounded-lg cursor-not-allowed flex items-center justify-center gap-2 hover:bg-gray-200 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Stok Habis
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 opacity-0 transform translate-y-10 animate-fadeInUp">
                        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-700 mb-2">Menu Tidak Ditemukan</h3>
                        <p class="text-gray-500 max-w-md mx-auto">Maaf, kami tidak dapat menemukan menu yang sesuai dengan kategori ini. Coba kategori lain atau kunjungi kami nanti.</p>
                        <a href="{{ route('menu.index') }}" class="mt-4 inline-block px-6 py-2 bg-gradient-to-r from-red-500 to-orange-500 text-white font-bold rounded-full shadow-md hover:shadow-lg transition-all">
                            Lihat Semua Menu
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- Pagination with Animation --}}
            @if($products->hasPages())
                <div class="mt-12 opacity-0 transform translate-y-10 animate-fadeInUp">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fadeInUp {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }
        .product-card:nth-child(1) { animation-delay: 0.1s; }
        .product-card:nth-child(2) { animation-delay: 0.2s; }
        .product-card:nth-child(3) { animation-delay: 0.3s; }
        .product-card:nth-child(4) { animation-delay: 0.4s; }
        .product-card:nth-child(5) { animation-delay: 0.5s; }
        .product-card:nth-child(6) { animation-delay: 0.6s; }
        .product-card:nth-child(7) { animation-delay: 0.7s; }
        .product-card:nth-child(8) { animation-delay: 0.8s; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const branchLocationData = @json($branchLocationData);
            const deliveryStatusMessage = document.getElementById('delivery-status-message');
            const orderButtons = document.querySelectorAll('.order-button');
            const cartLink = document.querySelector('a[href="{{ route('cart.index') }}"]');

            function haversineDistance(lat1, lon1, lat2, lon2) {
                const R = 6371;
                const dLat = (lat2 - lat1) * Math.PI / 180;
                const dLon = (lon2 - lon1) * Math.PI / 180;
                const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                          Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                          Math.sin(dLon / 2) * Math.sin(dLon / 2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                return R * c;
            }

            function updateUIForOrdering(canOrder, message = '') {
                orderButtons.forEach(button => {
                    button.disabled = !canOrder;
                    if (!canOrder) {
                        button.classList.add('bg-gray-400', 'cursor-not-allowed');
                        button.classList.remove('bg-red-600', 'hover:bg-red-700');
                    }
                });
                if (cartLink) {
                    cartLink.style.pointerEvents = canOrder ? 'auto' : 'none';
                    cartLink.classList.toggle('opacity-50', !canOrder);
                }
            }

            function checkDeliveryAvailability() {
                if (!branchLocationData || !branchLocationData.latitude || !branchLocationData.longitude) {
                    deliveryStatusMessage.innerHTML = `
                        <svg class="w-6 h-6 flex-shrink-0 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium"><span class="font-bold">Error!</span> Lokasi cabang belum diatur.</p>
                            <p class="text-xs mt-1">Anda tetap bisa memesan untuk takeaway.</p>
                        </div>`;
                    deliveryStatusMessage.className = 'max-w-3xl mx-auto bg-gradient-to-r from-red-100 to-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-r-lg mb-12 flex items-start gap-4 shadow-md hover:shadow-lg';
                    updateUIForOrdering(true);
                    return;
                }

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const userLat = position.coords.latitude;
                            const userLon = position.coords.longitude;
                            const distance = haversineDistance(branchLocationData.latitude, branchLocationData.longitude, userLat, userLon);

                            if (distance <= branchLocationData.radius_km) {
                                deliveryStatusMessage.innerHTML = `
                                    <svg class="w-6 h-6 flex-shrink-0 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium"><span class="font-bold">Kabar baik!</span> Lokasi Anda dalam jangkauan pengantaran (${distance.toFixed(2)} km).</p>
                                        <p class="text-xs mt-1 text-green-700">Pesanan Anda akan diantar dalam ${Math.round(distance * 5 + 20)} menit</p>
                                    </div>`;
                                deliveryStatusMessage.className = 'max-w-3xl mx-auto bg-gradient-to-r from-green-100 to-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-r-lg mb-12 flex items-start gap-4 shadow-md hover:shadow-lg';
                                updateUIForOrdering(true);
                            } else {
                                deliveryStatusMessage.innerHTML = `
                                    <svg class="w-6 h-6 flex-shrink-0 text-yellow-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium"><span class="font-bold">Mohon maaf,</span> Anda di luar jangkauan pengantaran (${distance.toFixed(2)} km).</p>
                                        <p class="text-xs mt-1 text-yellow-700">Silakan pilih opsi takeaway atau kunjungi cabang terdekat kami.</p>
                                    </div>`;
                                deliveryStatusMessage.className = 'max-w-3xl mx-auto bg-gradient-to-r from-yellow-100 to-yellow-50 border-l-4 border-yellow-500 text-yellow-800 p-4 rounded-r-lg mb-12 flex items-start gap-4 shadow-md hover:shadow-lg';
                                updateUIForOrdering(true); // Still allow ordering for takeaway
                            }
                        },
                        (error) => {
                            let errorMessage = "Tidak dapat mengakses lokasi. Mohon izinkan akses lokasi.";
                            if (error.code === error.PERMISSION_DENIED) {
                                errorMessage = "Akses lokasi ditolak. Anda dapat tetap memesan untuk takeaway.";
                            } else if (error.code === error.TIMEOUT) {
                                errorMessage = "Waktu permintaan lokasi habis. Coba refresh halaman.";
                            }

                            deliveryStatusMessage.innerHTML = `
                                <svg class="w-6 h-6 flex-shrink-0 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium"><span class="font-bold">Perhatian:</span> ${errorMessage}</p>
                                    <p class="text-xs mt-1 text-blue-700">Anda tetap bisa memesan untuk takeaway.</p>
                                </div>`;
                            deliveryStatusMessage.className = 'max-w-3xl mx-auto bg-gradient-to-r from-blue-100 to-blue-50 border-l-4 border-blue-500 text-blue-800 p-4 rounded-r-lg mb-12 flex items-start gap-4 shadow-md hover:shadow-lg';
                            updateUIForOrdering(true);
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 5000,
                            maximumAge: 0
                        }
                    );
                } else {
                    deliveryStatusMessage.innerHTML = `
                        <svg class="w-6 h-6 flex-shrink-0 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium"><span class="font-bold">Info:</span> Browser tidak mendukung geolokasi.</p>
                            <p class="text-xs mt-1 text-blue-700">Anda tetap bisa memesan untuk takeaway.</p>
                        </div>`;
                    deliveryStatusMessage.className = 'max-w-3xl mx-auto bg-gradient-to-r from-blue-100 to-blue-50 border-l-4 border-blue-500 text-blue-800 p-4 rounded-r-lg mb-12 flex items-start gap-4 shadow-md hover:shadow-lg';
                    updateUIForOrdering(true);
                }
            }

            // Delay the check slightly for better UX
            setTimeout(checkDeliveryAvailability, 1500);
        });
    </script>
@endsection
