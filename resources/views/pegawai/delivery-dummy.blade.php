@extends('layouts.app')

@section('content')
<div class="container mx-auto py-16 px-4">
    <div class="bg-white rounded-2xl shadow-xl p-10 text-center max-w-xl mx-auto animate-fade-in">
        <svg class="w-16 h-16 mx-auto mb-4 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6"/></svg>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Halaman Pengantaran Pegawai</h2>
        <p class="text-gray-500 mb-4">Fitur ini akan segera tersedia.</p>
        <a href="{{ route('pegawai.dashboard') }}" class="inline-block mt-4 bg-red-600 hover:bg-red-700 text-white font-bold px-6 py-3 rounded-xl shadow transition-all">Kembali ke Dashboard</a>
    </div>
</div>
@endsection
