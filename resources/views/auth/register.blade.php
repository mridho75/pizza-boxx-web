@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-md py-8">
    <h2 class="text-3xl font-bold mb-6 text-center text-red-600">Registrasi</h2>
    <form method="POST" action="{{ route('register') }}" class="bg-white p-6 rounded-lg shadow-lg">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-gray-700">Nama</label>
            <input type="text" name="name" id="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400" value="{{ old('name') }}" required autofocus>
            @error('name')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
        </div>
        <div class="mb-4">
            <label for="email" class="block text-gray-700">Email</label>
            <input type="email" name="email" id="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400" value="{{ old('email') }}" required>
            @error('email')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
        </div>
        <div class="mb-4">
            <label for="password" class="block text-gray-700">Password</label>
            <input type="password" name="password" id="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400" required>
            @error('password')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
        </div>
        <div class="mb-4">
            <label for="password_confirmation" class="block text-gray-700">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400" required>
        </div>
        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">Daftar</button>
        <div class="mt-4 text-center">
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Sudah punya akun? Login</a>
        </div>
    </form>
</div>
@endsection
