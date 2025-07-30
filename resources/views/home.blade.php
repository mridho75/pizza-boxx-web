@extends('layouts.app')

@section('content')
    <div class="relative bg-cover bg-center h-[500px] flex items-center justify-center text-white" style="background-image: url('{{ asset('images/pizza-boxx-background.jpg') }}');">
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="relative z-10 text-center">
            <h1 class="text-5xl font-extrabold mb-4 animate-fade-in-down">Pizza Boxx</h1>
            <p class="text-xl mb-8 animate-fade-in-up">Good Pizza, Great Pizza</p>
            <a href="{{ route('menu.index') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-full text-lg transition-all duration-300 animate-scale-up">
                Pesan Sekarang
            </a>
        </div>
    </div>

    <section class="py-16 bg-white text-center"> {{-- Perubahan: bg-white --}}
        <h2 class="text-4xl font-bold text-red-500 mb-8">Mengapa Memilih Pizza Boxx?</h2>
        <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-gray-100 p-6 rounded-lg shadow-lg transform hover:scale-105 transition-transform duration-300"> {{-- Perubahan: bg-gray-100 --}}
                <i class="heroicon-o-truck text-red-500 text-5xl mb-4"></i> {{-- Placeholder for icon --}}
                <h3 class="text-2xl font-semibold text-gray-800 mb-2">Pengiriman Cepat</h3> {{-- Perubahan: text-gray-800 --}}
                <p class="text-gray-600">Pizza panas langsung ke pintu Anda dalam sekejap.</p> {{-- Perubahan: text-gray-600 --}}
            </div>
            <div class="bg-gray-100 p-6 rounded-lg shadow-lg transform hover:scale-105 transition-transform duration-300"> {{-- Perubahan: bg-gray-100 --}}
                <i class="heroicon-o-heart text-red-500 text-5xl mb-4"></i> {{-- Placeholder for icon --}}
                <h3 class="text-2xl font-semibold text-gray-800 mb-2">Bahan Berkualitas</h3> {{-- Perubahan: text-gray-800 --}}
                <p class="text-gray-600">Hanya bahan-bahan segar pilihan terbaik untuk setiap pizza.</p> {{-- Perubahan: text-gray-600 --}}
            </div>
            <div class="bg-gray-100 p-6 rounded-lg shadow-lg transform hover:scale-105 transition-transform duration-300"> {{-- Perubahan: bg-gray-100 --}}
                <i class="heroicon-o-star text-red-500 text-5xl mb-4"></i> {{-- Placeholder for icon --}}
                <h3 class="text-2xl font-semibold text-gray-800 mb-2">Rasa Tak Terlupakan</h3> {{-- Perubahan: text-gray-800 --}}
                <p class="text-gray-600">Resep rahasia kami menjamin pengalaman rasa yang luar biasa.</p> {{-- Perubahan: text-gray-600 --}}
            </div>
        </div>
    </section>

    <section class="py-16 bg-gray-100 text-center"> {{-- Perubahan: bg-gray-100 --}}
        <h2 class="text-4xl font-bold text-red-500 mb-8">Pesan Pizza Favorit Anda Sekarang!</h2>
        <p class="text-xl text-gray-600 mb-8">Siap untuk menikmati kelezatan Pizza Boxx?</p> {{-- Perubahan: text-gray-600 --}}
        <a href="{{ route('menu.index') }}" class="bg-red-500 hover:bg-red-600 text-white font-bold py-4 px-10 rounded-full text-xl transition-all duration-300">
            Lihat Menu Lengkap
        </a>
    </section>
@endsection

<style>
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes scaleUp {
        from { opacity: 0; transform: scale(0.8); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-fade-in-down { animation: fadeInDown 1s ease-out forwards; }
    .animate-fade-in-up { animation: fadeInUp 1s ease-out forwards; animation-delay: 0.3s; }
    .animate-scale-up { animation: scaleUp 0.8s ease-out forwards; animation-delay: 0.6s; }
</style>