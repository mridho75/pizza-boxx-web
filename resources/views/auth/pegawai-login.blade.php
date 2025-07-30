@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-50">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold text-center text-red-600 mb-6">Login Pegawai</h2>
        @if(session('error'))
            <div class="bg-red-500 text-white p-3 rounded-lg mb-4 text-center">
                {{ session('error') }}
            </div>
        @endif
        <form method="POST" action="{{ route('pegawai.login') }}">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                <input id="email" type="email" name="email" required autofocus class="w-full p-3 rounded-lg bg-gray-100 border border-gray-300 focus:ring-red-500 focus:border-red-500">
            </div>
            <div class="mb-6">
                <label for="password" class="block text-gray-700 font-semibold mb-2">Password</label>
                <input id="password" type="password" name="password" required class="w-full p-3 rounded-lg bg-gray-100 border border-gray-300 focus:ring-red-500 focus:border-red-500">
            </div>
            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">Login</button>
        </form>
    </div>
</div>
@endsection
