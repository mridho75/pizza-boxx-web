@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-md py-12">
    <h2 class="text-2xl font-bold mb-6 text-center">Verifikasi QR Pesanan Takeaway</h2>
    @if(session('success'))
        <div class="bg-green-500 text-white p-3 rounded-lg mb-4 text-center">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-500 text-white p-3 rounded-lg mb-4 text-center">{{ session('error') }}</div>
    @endif
    <form method="POST" action="{{ route('qr.verify') }}" class="bg-white p-6 rounded-xl shadow-lg flex flex-col gap-4">
        @csrf
        <label class="font-semibold">Kode QR (atau paste hasil scan):</label>
        <input type="text" name="qr_code" class="border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-400" required autofocus>
        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-full transition-colors mt-2">Verifikasi</button>
    </form>
</div>
@endsection
