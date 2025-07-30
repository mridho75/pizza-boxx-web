@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-md py-8">
    <h2 class="text-3xl font-bold mb-6 text-center text-red-600">Login</h2>
    <form method="POST" action="{{ route('login') }}" class="bg-white p-6 rounded-lg shadow-lg">
        @csrf
        <div class="mb-4">
            <label for="email" class="block text-gray-700">Email</label>
            <input type="email" name="email" id="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400" value="{{ old('email') }}" required autofocus>
            @error('email')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
        </div>
        <div class="mb-4">
            <label for="password" class="block text-gray-700">Password</label>
            <input type="password" name="password" id="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400" required>
            @error('password')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
        </div>
        <div class="mb-4 flex items-center">
            <input type="checkbox" name="remember" id="remember" class="mr-2">
            <label for="remember" class="text-gray-700">Ingat saya</label>
        </div>
        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">Login</button>
        <div class="mt-4 text-center">
            <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Belum punya akun? Daftar</a>
        </div>
    </form>
</div>
@endsection
