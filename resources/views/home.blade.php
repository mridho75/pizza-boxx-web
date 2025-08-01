@extends('layouts.app')

@section('content')

    <div class="relative h-[520px] flex items-center justify-center text-white overflow-hidden">
        <div class="absolute inset-0 w-full h-full">
            <img src="{{ asset('images/pizzabanner.jpg') }}" alt="Pizza Banner" class="w-full h-full object-cover object-center blur-sm scale-105" style="filter: blur(6px);" loading="lazy">
            <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/40 to-transparent"></div>
        </div>
        <div class="relative z-20 text-center animate-fade-in-down">
            <h1 class="text-6xl md:text-7xl font-extrabold mb-4 bg-gradient-to-r from-yellow-300 via-red-500 to-red-700 bg-clip-text text-transparent drop-shadow-2xl animate-fade-in-down shadow-yellow-200">
                <span class="inline-block animate-text-pop">Pizza Boxx</span>
            </h1>
            <p class="text-2xl md:text-3xl mb-8 font-semibold tracking-wide text-white/90 drop-shadow-lg animate-fade-in-up animate-delay-300">
                <span class="inline-block animate-text-fade">Good Pizza, Great Pizza</span>
            </p>
            <a href="{{ route('menu.index') }}" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-full text-lg shadow-lg transition-all duration-300 animate-scale-up">
                <svg class="w-6 h-6 animate-bounce" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                Pesan Sekarang
            </a>
            <div class="mt-6 flex flex-col items-center gap-2 animate-fade-in-up animate-delay-700">
                <div class="flex items-center gap-1 text-yellow-400 text-2xl">
                    <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                </div>
                <div class="text-white/90 text-base font-medium">1,000+ Ulasan Puas</div>
                <div class="text-white/80 italic text-sm max-w-md animate-slide-in-up animate-delay-1000">"Pizza terenak, pengiriman super cepat! Anak-anak saya suka banget!" <span class="not-italic">- Rina, Surabaya</span></div>
            </div>
        </div>
        <svg class="absolute bottom-0 left-0 w-full h-20" viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path d="M0,40 C360,120 1080,0 1440,40 L1440,80 L0,80 Z" fill="#fff"/>
        </svg>
    </div>

    <section class="py-16 bg-white text-center">
        <h2 class="text-4xl font-extrabold mb-4 text-gray-900">Mengapa Memilih Pizza Boxx?</h2>
        <p class="text-lg text-gray-500 mb-10">Kami tidak hanya membuat pizza, kami menciptakan kebahagiaan dalam sebuah kotak.</p>
        <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-2xl shadow-xl border-t-4 border-red-400 flex flex-col items-center transition-all duration-300 hover:shadow-2xl group">
                <div class="w-20 h-20 flex items-center justify-center rounded-full bg-red-100 mb-6">
                    <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Pengiriman Cepat</h3>
                <p class="text-gray-600">Pizza panas dan segar langsung ke pintu Anda, lebih cepat dari yang Anda kira.</p>
            </div>
            <div class="bg-white p-8 rounded-2xl shadow-xl border-t-4 border-red-400 flex flex-col items-center transition-all duration-300 hover:shadow-2xl group">
                <div class="w-20 h-20 flex items-center justify-center rounded-full bg-red-100 mb-6">
                    <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Bahan Berkualitas</h3>
                <p class="text-gray-600">Kami hanya menggunakan bahan-bahan segar pilihan untuk rasa yang otentik.</p>
            </div>
            <div class="bg-white p-8 rounded-2xl shadow-xl border-t-4 border-red-400 flex flex-col items-center transition-all duration-300 hover:shadow-2xl group">
                <div class="w-20 h-20 flex items-center justify-center rounded-full bg-red-100 mb-6">
                    <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Rasa Juara</h3>
                <p class="text-gray-600">Setiap gigitan adalah perpaduan sempurna dari resep rahasia dan cinta.</p>
            </div>
        </div>
    </section>

    <section class="py-16 bg-gray-100 text-center">
        <h2 class="text-4xl font-bold text-red-500 mb-8 animate-fade-in-down">Pesan Pizza Favorit Anda Sekarang!</h2>
        <p class="text-xl text-gray-600 mb-8 animate-fade-in-up">Siap untuk menikmati kelezatan Pizza Boxx?</p>
        <a href="{{ route('menu.index') }}" class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white font-bold py-4 px-10 rounded-full text-xl shadow-lg transition-all duration-300 animate-scale-up">
            <svg class="w-7 h-7 animate-bounce" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            Lihat Menu Lengkap
        </a>
    </section>
@endsection

@push('styles')

<style>
.animate-shimmer {
    animation: shimmerMove 12s linear infinite;
}
@keyframes shimmerMove {
    0% { opacity: 0.18; }
    50% { opacity: 0.32; }
    100% { opacity: 0.18; }
}
@keyframes slideInUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}



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
@keyframes wiggle {
    0%, 100% { transform: rotate(-6deg); }
    50% { transform: rotate(6deg); }
}
@keyframes textPop {
    0% { transform: scale(0.9); opacity: 0; }
    60% { transform: scale(1.1); opacity: 1; }
    100% { transform: scale(1); opacity: 1; }
}
@keyframes textFade {
    0% { opacity: 0; }
    100% { opacity: 1; }
}
.animate-slide-in-up { animation: slideInUp 1.1s cubic-bezier(.23,1.12,.72,.98) both; }
.animate-delay-700 { animation-delay: 0.7s; }
.animate-delay-1000 { animation-delay: 1s; }
.animate-fade-in-down { animation: fadeInDown 1s ease-out forwards; }
.animate-fade-in-up { animation: fadeInUp 1s ease-out forwards; animation-delay: 0.3s; }
.animate-scale-up { animation: scaleUp 0.8s ease-out forwards; animation-delay: 0.6s; }
.animate-bounce { animation: bounce 1.2s infinite alternate; }
.group-hover\:animate-wiggle:hover { animation: wiggle 0.5s infinite; }
.animate-text-pop { animation: textPop 1.1s cubic-bezier(.23,1.12,.72,.98) both; }
.animate-text-fade { animation: textFade 1.2s 0.5s ease-out both; }
.animate-delay-300 { animation-delay: 0.3s; }
</style>
@endpush
