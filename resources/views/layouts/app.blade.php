<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pizza Boxx</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <!-- Tailwind CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Alpine.js for dropdown -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-100 text-gray-800"> {{-- Perubahan: bg-gray-100 dan text-gray-800 --}}
    {{-- Toast Notification (top right) --}}
    @if(session('success') === 'Keranjang berhasil diperbarui' || session('success') === 'Keranjang berhasil dikosongkan.')
    <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" x-init="setTimeout(() => show = false, 2500)" class="fixed top-6 right-6 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2">
        <svg class="h-6 w-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
        <span class="font-semibold">{{ session('success') }}</span>
    </div>
    @endif
    <div class="min-h-screen flex flex-col">
        {{-- Header/Navbar --}}
        <header class="bg-white text-gray-800 shadow-lg fixed top-0 left-0 w-full z-30"> {{-- Navbar fixed --}}
            <nav class="container mx-auto px-4 py-4 flex justify-between items-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold tracking-wider text-red-800 transition-transform duration-200 ease-in-out hover:scale-105">Pizza Boxx</a> {{-- Perubahan: text-red-800, hover:scale --}}
                <div class="flex items-center gap-2 sm:gap-4 md:gap-6 lg:gap-8">
                    <a href="{{ route('menu.index') }}" class="flex items-center h-10 px-3 rounded-lg hover:bg-red-50 hover:text-red-600 transition-all duration-200 font-medium">Menu</a>
                    <a href="#" class="flex items-center h-10 px-3 rounded-lg hover:bg-red-50 hover:text-red-600 transition-all duration-200 font-medium">Promo</a>
                    <a href="#" class="flex items-center h-10 px-3 rounded-lg hover:bg-red-50 hover:text-red-600 transition-all duration-200 font-medium">Kontak</a>

                    <a href="{{ route('cart.index') }}" class="relative flex items-center justify-center w-10 h-10 transition-colors group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-gray-700 group-hover:text-red-600 transition-colors duration-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="21" r="1"/>
                            <circle cx="20" cy="21" r="1"/>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                        </svg>
                        @php $cartCount = session('cart') ? count(session('cart')) : 0; @endphp
                        @if($cartCount > 0)
                            <span id="cart-count" class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center border-2 border-white shadow transition-all duration-200">{{ $cartCount }}</span>
                        @endif
                    </a>

                    {{-- User Icon + Name + Dropdown (icon always, name if login, dropdown if login) --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @blur="setTimeout(() => open = false, 150)" class="flex items-center h-10 px-3 rounded-lg hover:bg-red-50 hover:text-red-600 transition-all duration-200 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-gray-700 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            @auth
                                <span class="font-medium text-base text-gray-700">{{ ucfirst(Str::of(Auth::user()->name)->explode(' ')->first()) }}</span>
                            @endauth
                        </button>
                        @auth
                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-100" style="display: none;">
                            <div class="px-4 py-2 border-b">
                                <div class="font-bold text-lg text-gray-800">{{ ucfirst(Str::of(Auth::user()->name)->explode(' ')->first()) }}</div>
                                <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
                            </div>
                            <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                Akun Saya
                            </a>
                            <a href="{{ route('user.dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v4a1 1 0 001 1h3m10-5v4a1 1 0 01-1 1h-3m-4 4h4m-2 0v4m0-4V7" /></svg>
                                Orderan Saya
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="px-4 py-2">
                                @csrf
                                <button type="submit" class="flex items-center text-gray-700 hover:bg-gray-100 w-full transition-colors">
                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7" /></svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                        @endauth
                    </div>
                </div>
            </nav>
        </header>

        {{-- TEMPAT MENAMPILKAN PESAN SUKSES/ERROR --}}
        @if(session('success') && session('success') !== 'Produk berhasil ditambahkan ke keranjang!' && session('success') !== 'Keranjang berhasil dikosongkan.')
            <div class="bg-green-500 text-white p-4 text-center">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-500 text-white p-4 text-center">
                {{ session('error') }}
            </div>
        @endif

        {{-- Main Content Slot --}}
        <main class="flex-grow pt-25"> {{-- Tambah padding atas agar tidak tertutup navbar --}}
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="bg-gray-800 text-gray-300 py-6 mt-12"> {{-- Perubahan: text-gray-300 (lebih terang) --}}
            <div class="container mx-auto px-4 text-center">
                <p>&copy; {{ date('Y') }} Pizza Boxx. All rights reserved.</p>
                <p class="text-sm mt-2">Dibuat dengan Laravel & Filament untuk Tugas Akhir.</p>
            </div>
        </footer>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cartCountSpan = document.getElementById('cart-count');
            if (cartCountSpan) {
                // This count is initially rendered by Blade, but can be updated via JS if needed for SPA-like behavior
                // For now, it relies on page reload after add to cart.
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
