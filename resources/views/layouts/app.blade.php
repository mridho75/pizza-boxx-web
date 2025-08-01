<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pizza Boxx - Authentic Italian Pizza</title>
    <meta name="description" content="Order delicious authentic Italian pizza with fresh ingredients. Fast delivery and great taste guaranteed.">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Preload critical assets -->
    <link rel="preload" href="{{ asset('images/pizza-boxx-logo.png') }}" as="image">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js with defer -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
</head>
<body class="font-sans antialiased bg-white text-gray-800">
    <!-- Success Notification -->
    @if(session('success'))
        <div x-data="{ show: true }"
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-4"
             x-init="setTimeout(() => show = false, 2500)"
             class="fixed top-6 right-6 z-50 bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-xl shadow-xl flex items-center space-x-3 border-l-4 border-white/20">
            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="min-h-screen flex flex-col">
        <!-- Employee Sidebar -->
        @if(auth()->check() && (auth()->user()->hasRole('employee') || auth()->user()->hasRole('admin')) && request()->is('pegawai*'))
            <aside class="hidden md:flex flex-col w-64 bg-white border-r border-gray-200 pt-24 px-4 fixed left-0 top-0 h-full z-30 shadow-lg">
                <div class="flex flex-col gap-8">
                    <div class="flex flex-col items-center gap-2 mb-6 animate-fade-in">
                        <img src="{{ asset('images/pizza-boxx-logo.png') }}" alt="Logo" class="h-16 w-16 rounded-full shadow mb-2 transition-transform duration-300 hover:scale-110">
                        <div class="font-bold text-lg text-red-600">Pegawai Panel</div>
                    </div>
                    <nav class="flex flex-col gap-1">
                        <a href="{{ route('pegawai.dashboard') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg font-semibold transition-all duration-300
                                  {{ request()->routeIs('pegawai.dashboard') ? 'bg-red-100 text-red-600 shadow-inner' : 'text-gray-700 hover:bg-red-50 hover:text-red-600' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18"/>
                            </svg>
                            Dashboard
                        </a>
                        <a href="{{ route('pegawai.deliveries.index') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-lg font-semibold transition-all duration-300
                                  {{ request()->routeIs('pegawai.deliveries.index') ? 'bg-red-100 text-red-600 shadow-inner' : 'text-gray-700 hover:bg-red-50 hover:text-red-600' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6"/>
                            </svg>
                            Pengantaran
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit"
                                    class="flex items-center gap-3 px-4 py-3 rounded-lg font-semibold text-left w-full transition-all duration-300
                                           text-gray-700 hover:bg-red-50 hover:text-red-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                                </svg>
                                Keluar
                            </button>
                        </form>
                    </nav>
                </div>
            </aside>
            <div class="md:ml-64 flex-1 flex flex-col">
        @else
            <div class="flex-1 flex flex-col">
        @endif

        <!-- Main Navigation -->
        <header class="bg-white text-gray-800 shadow-md fixed top-0 left-0 w-full z-40 backdrop-blur-sm bg-white/90">
            <nav class="container mx-auto px-4 py-3 grid grid-cols-3 items-center">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group transition-all duration-200">
                        <img src="{{ asset('images/pizza-boxx-logo.png') }}"
                             alt="Pizza Boxx Logo"
                             class="h-10 w-10 object-contain drop-shadow-md transition-transform duration-300 group-hover:rotate-[15deg]"
                             loading="eager">
                        <span class="hidden sm:block text-xl font-bold text-red-600 group-hover:text-red-700 transition-colors">Pizza Boxx</span>
                    </a>
                </div>

                <!-- Main Navigation Links -->
                <div class="flex items-center justify-center gap-4 md:gap-6">
                    <a href="{{ route('home') }}"
                       class="group flex items-center h-10 px-3 rounded-lg font-bold relative transition-all duration-300 whitespace-nowrap
                              {{ request()->routeIs('home') ? 'text-red-600' : 'text-gray-700 hover:text-red-600' }}">
                        HOME
                        <span class="absolute left-1/2 -bottom-1 -translate-x-1/2 h-0.5 bg-red-500 rounded-full transition-all duration-300
                                    {{ request()->routeIs('home') ? 'w-8' : 'w-0 group-hover:w-8' }}"></span>
                    </a>
                    <a href="{{ route('menu.index') }}"
                       class="group flex items-center h-10 px-3 rounded-lg font-bold relative transition-all duration-300 whitespace-nowrap
                              {{ request()->routeIs('menu.index') ? 'text-red-600' : 'text-gray-700 hover:text-red-600' }}">
                        MENU
                        <span class="absolute left-1/2 -bottom-1 -translate-x-1/2 h-0.5 bg-red-500 rounded-full transition-all duration-300
                                    {{ request()->routeIs('menu.index') ? 'w-8' : 'w-0 group-hover:w-8' }}"></span>
                    </a>
                    <a href="{{ route('about') }}"
                       class="group flex items-center h-10 px-3 rounded-lg font-bold relative transition-all duration-300 whitespace-nowrap
                              {{ request()->routeIs('about') ? 'text-red-600' : 'text-gray-700 hover:text-red-600' }}">
                        ABOUT
                        <span class="absolute left-1/2 -bottom-1 -translate-x-1/2 h-0.5 bg-red-500 rounded-full transition-all duration-300
                                    {{ request()->routeIs('about') ? 'w-8' : 'w-0 group-hover:w-8' }}"></span>
                    </a>
                    <a href="{{ route('contact') }}"
                       class="group flex items-center h-10 px-3 rounded-lg font-bold relative transition-all duration-300 whitespace-nowrap
                              {{ request()->routeIs('contact') ? 'text-red-600' : 'text-gray-700 hover:text-red-600' }}">
                        CONTACT
                        <span class="absolute left-1/2 -bottom-1 -translate-x-1/2 h-0.5 bg-red-500 rounded-full transition-all duration-300
                                    {{ request()->routeIs('contact') ? 'w-8' : 'w-0 group-hover:w-8' }}"></span>
                    </a>
                </div>

                <!-- User Actions -->
                <div class="flex items-center justify-end gap-3 sm:gap-5">
                    <!-- Cart -->
                    <a href="{{ route('cart.index') }}"
                       class="relative flex items-center justify-center w-10 h-10 transition-all duration-300 group focus:outline-none"
                       aria-label="Keranjang">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6 text-gray-700 group-hover:text-red-600 transition-colors duration-200"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round">
                            <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                        </svg>
                        @php $cartCount = session('cart') ? count(session('cart')) : 0; @endphp
                        @if($cartCount > 0)
                            <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center border-2 border-white shadow-md transition-all duration-200 group-hover:animate-bounce">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        @guest
                            <a href="{{ route('login') }}"
                               class="flex items-center h-10 px-4 sm:px-6 rounded-xl bg-gradient-to-r from-red-600 to-red-500 text-white font-semibold shadow-md hover:from-red-700 hover:to-red-600 transition-all duration-200 hover:shadow-lg">
                                Masuk
                            </a>
                        @endguest
                        @auth
                            <button @click="open = !open"
                                    @blur="setTimeout(() => open = false, 150)"
                                    class="flex items-center h-10 px-3 rounded-lg hover:bg-red-50 hover:text-red-600 transition-all duration-200 focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-6 w-6 text-gray-700 mr-2"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="font-bold text-base text-gray-700 hidden sm:inline-block">
                                    {{ ucfirst(Str::of(Auth::user()->name)->explode(' ')->first()) }}
                                </span>
                            </button>
                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl py-2 z-50 border border-gray-100 divide-y divide-gray-100"
                                 style="display: none;">
                                <!-- User Info -->
                                <div class="px-4 py-3">
                                    <div class="font-bold text-gray-800 truncate">{{ Auth::user()->name }}</div>
                                    <div class="text-sm text-gray-500 truncate">{{ Auth::user()->email }}</div>
                                </div>
                                <!-- Menu Items -->
                                <div class="py-1">
                                    <a href="{{ route('user.profile') }}"
                                       class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors">
                                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        Akun Saya
                                    </a>
                                    <a href="{{ route('user.dashboard') }}"
                                       class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors">
                                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        Orderan Saya
                                    </a>
                                </div>
                                <!-- Logout -->
                                <div class="py-1">
                                    <form action="{{ route('logout') }}" method="POST" class="w-full">
                                        @csrf
                                        <button type="submit"
                                                class="w-full text-left flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors">
                                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                            </svg>
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>
            </nav>
        </header>

        <!-- Promo Marquee -->
        <div class="group fixed top-16 left-0 w-full z-30 bg-gradient-to-r from-red-600 to-orange-500 text-white py-2 overflow-hidden shadow-lg">
            <div class="animate-marquee inline-block whitespace-nowrap will-change-transform">
                @for ($i = 0; $i < 10; $i++)
                    <span class="mx-8 text-sm font-bold tracking-wider" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">
                        🍕 DISKON 10% dengan kode <span class="uppercase bg-white/20 px-2 py-1 rounded">DISKON10</span> 🍕
                    </span>
                @endfor
            </div>
        </div>

        <!-- Main Content -->
        <main class="flex-grow pt-20">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-[#131a26] text-gray-300 pt-12 pb-6 border-t-4 border-red-600">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 pb-8">
                    <!-- Brand Info -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2">
                            <img src="{{ asset('images/pizza-boxx-logo.png') }}" alt="Pizza Boxx Logo" class="h-10 w-10">
                            <span class="text-2xl font-bold text-red-400">Pizza Boxx</span>
                        </div>
                        <p class="text-gray-400 text-sm leading-relaxed">
                            Authentic Italian pizza made with love and the freshest ingredients since 2010.
                        </p>
                        <div class="flex items-center gap-4 text-lg pt-2">
                            <a href="#" class="text-gray-400 hover:text-white transition-colors" aria-label="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors" aria-label="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors" aria-label="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors" aria-label="TikTok">
                                <i class="fab fa-tiktok"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="font-bold text-lg text-white mb-4">Quick Links</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('home') }}" class="hover:text-red-400 transition-colors">Home</a></li>
                            <li><a href="{{ route('menu.index') }}" class="hover:text-red-400 transition-colors">Menu</a></li>
                            <li><a href="{{ route('about') }}" class="hover:text-red-400 transition-colors">About Us</a></li>
                            <li><a href="{{ route('contact') }}" class="hover:text-red-400 transition-colors">Contact</a></li>
                            <li><a href="{{ route('cart.index') }}" class="hover:text-red-400 transition-colors">My Cart</a></li>
                        </ul>
                    </div>

                    <!-- Legal -->
                    <div>
                        <h3 class="font-bold text-lg text-white mb-4">Legal</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="hover:text-red-400 transition-colors">Privacy Policy</a></li>
                            <li><a href="#" class="hover:text-red-400 transition-colors">Terms of Service</a></li>
                            <li><a href="#" class="hover:text-red-400 transition-colors">Refund Policy</a></li>
                            <li><a href="#" class="hover:text-red-400 transition-colors">Delivery Policy</a></li>
                        </ul>
                    </div>

                    <!-- Contact Info -->
                    <div>
                        <h3 class="font-bold text-lg text-white mb-4">Contact Us</h3>
                        <address class="not-italic space-y-2">
                            <div class="flex items-start gap-2">
                                <i class="fas fa-map-marker-alt mt-1 text-red-400"></i>
                                <span>123 Pizza Street, Sukahati, Indonesia</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-phone text-red-400"></i>
                                <a href="tel:+6281234567890" class="hover:text-red-400 transition-colors">+62 812 3456 7890</a>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-envelope text-red-400"></i>
                                <a href="mailto:info@pizzaboxx.com" class="hover:text-red-400 transition-colors">info@pizzaboxx.com</a>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-clock text-red-400"></i>
                                <span>Open Daily: 10:00 - 22:00</span>
                            </div>
                        </address>
                    </div>
                </div>

                <!-- Copyright -->
                <div class="border-t border-gray-700 pt-6 flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="text-gray-400 text-sm">
                        &copy; {{ date('Y') }} Pizza Boxx. All rights reserved.
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @stack('scripts')

    <style>
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .animate-marquee {
            animation: marquee 30s linear infinite;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        .animate-bounce {
            animation: bounce 0.8s infinite;
        }
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.5s ease-out forwards;
        }
    </style>
</body>
</html>
