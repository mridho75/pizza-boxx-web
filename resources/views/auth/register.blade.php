@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 p-4">
    <div class="w-full max-w-4xl mx-auto grid md:grid-cols-2 bg-white rounded-2xl shadow-2xl overflow-hidden">

        <!-- Kolom Kiri: Formulir Registrasi -->
        <div class="p-8 md:p-12 flex flex-col justify-center">
            <div class="mb-8 text-center">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('images/pizza-boxx-logo.png') }}" alt="Pizza Boxx Logo" class="w-20 h-20 mx-auto mb-4">
                </a>
                <h1 class="text-3xl font-extrabold text-gray-900">Buat Akun Baru</h1>
                <p class="text-gray-600 mt-1">Satu langkah lagi menuju pizza terlezat!</p>
            </div>

            {{-- Menampilkan pesan error jika ada --}}
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg" role="alert">
                    <p class="font-bold">Oops! Terjadi kesalahan.</p>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Input Nama -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </span>
                        <input id="name" name="name" type="text" autocomplete="name" required
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all"
                               placeholder="John Doe" value="{{ old('name') }}">
                    </div>
                </div>

                <!-- Input Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                        </span>
                        <input id="email" name="email" type="email" autocomplete="email" required
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all"
                               placeholder="anda@email.com" value="{{ old('email') }}">
                    </div>
                </div>

                <!-- Input Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </span>
                        <input id="password" name="password" type="password" autocomplete="new-password" required
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all"
                               placeholder="••••••••">
                    </div>
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                           <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </span>
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all"
                               placeholder="••••••••">
                    </div>
                </div>

                <!-- Tombol Daftar -->
                <div>
                    <button type="submit"
                            class="w-full flex justify-center py-3 px-4 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                        Daftar
                    </button>
                </div>
            </form>

            <p class="mt-8 text-center text-sm text-gray-600">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-semibold text-red-600 hover:text-red-500">Login di sini</a>
            </p>
        </div>

        <!-- Kolom Kanan: Gambar -->
        <div class="hidden md:block">
            <img src="{{ asset('images/pizzabanner.jpg') }}" alt="Pizza lezat" class="w-full h-full object-cover">
        </div>

    </div>
</div>
@endsection
