@extends('layouts.app')

@section('content')

<section class="bg-gradient-to-b from-orange-50 to-white py-24">
    <div class="container mx-auto px-4">
        <!-- Modern Grid Layout -->
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <!-- Image Column with Floating Effect -->
            <div class="relative group">
                <!-- Main Image with Gradient Overlay -->
                <div class="relative overflow-hidden rounded-3xl shadow-2xl transform group-hover:-translate-y-2 transition-all duration-500">
                    <img src="{{ asset('images/aboutus.jpeg') }}" alt="Tim Pizza Boxx sedang menyiapkan pizza"
                         class="w-full h-[600px] object-cover transform group-hover:scale-105 transition-all duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                </div>

                <!-- Floating Statistic Badge -->
                <div class="absolute -bottom-6 -right-6 bg-gradient-to-r from-red-600 to-orange-500 text-white text-center p-6 rounded-2xl shadow-2xl z-10 transform group-hover:rotate-6 transition-all duration-300">
                    <span class="text-4xl font-bold block leading-none">14+</span>
                    <span class="text-sm font-medium">Tahun Melayani</span>
                </div>

                <!-- Decorative Elements -->
                <div class="absolute -top-6 -left-6 w-24 h-24 border-4 border-orange-400 rounded-xl opacity-20 group-hover:opacity-40 transition-all duration-500"></div>
                <div class="absolute bottom-16 -left-8 w-16 h-16 border-4 border-red-400 rounded-full opacity-20 group-hover:opacity-40 transition-all duration-700"></div>
            </div>

            <!-- Content Column -->
            <div class="lg:pl-12">
                <!-- Section Header with Gradient Text -->
                <div class="mb-2">
                    <span class="text-sm font-bold tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-orange-500">
                        CERITA KAMI
                    </span>
                </div>

                <!-- Main Heading with Animated Underline -->
                <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6 relative inline-block">
                    <span>Lebih dari Sekedar Pizza,</span>
                    <span class="block">Ini Tentang Keluarga.</span>
                    <span class="absolute bottom-0 left-0 w-1/3 h-1 bg-gradient-to-r from-red-500 to-orange-400 rounded-full"></span>
                </h2>

                <!-- Description Text -->
                <p class="text-lg text-gray-600 leading-relaxed mb-8">
                    Sejak 2010, Pizza Boxx lahir dari dapur keluarga kami dengan satu mimpi: menyajikan pizza otentik yang bisa menyatukan semua orang. Setiap adonan kami olah dengan sabar dan setiap saus kami racik dari resep warisan, menggunakan bahan-bahan segar pilihan untuk menghadirkan kehangatan dan kebahagiaan di setiap gigitan.
                </p>

                <!-- Value Propositions -->
                <div class="space-y-6 mb-10">
                    <!-- Value 1 -->
                    <div class="flex items-start gap-5 p-4 bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                        <div class="flex-shrink-0 bg-gradient-to-br from-red-100 to-orange-50 rounded-xl p-3">
                            <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-12v4m-2-2h4m5 4v4m-2-2h4M5 3a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2H5z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-xl text-gray-800 mb-1">Resep Asli Warisan Keluarga</h3>
                            <p class="text-gray-500">Rasa otentik yang kami jaga dari generasi ke generasi.</p>
                        </div>
                    </div>

                    <!-- Value 2 -->
                    <div class="flex items-start gap-5 p-4 bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                        <div class="flex-shrink-0 bg-gradient-to-br from-red-100 to-orange-50 rounded-xl p-3">
                            <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-xl text-gray-800 mb-1">Bahan Segar dari Pemasok Lokal</h3>
                            <p class="text-gray-500">Kami mendukung komunitas dengan memilih bahan terbaik dari sekitar kita.</p>
                        </div>
                    </div>
                </div>

                <!-- Animated CTA Button -->
                <a href="{{ route('menu.index') }}"
                   class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-red-600 to-orange-500 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 group">
                    <span class="mr-2">Jelajahi Menu Kami</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
