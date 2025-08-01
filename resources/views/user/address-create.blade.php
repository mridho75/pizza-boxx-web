@extends('layouts.app')

@section('title', 'Tambah Alamat Baru')

@section('content')
<div class="max-w-xl mx-auto py-10">
    <h2 class="text-2xl font-bold mb-6">Tambah Alamat Baru</h2>
    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('user.address.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Label Alamat (misal: Rumah, Kantor)</label>
                <input type="text" name="label" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Alamat Lengkap</label>
                <textarea name="address" class="w-full border rounded px-3 py-2" rows="3" required></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Kota</label>
                <input type="text" name="city" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Provinsi</label>
                <input type="text" name="province" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Nomor Telepon</label>
                <input type="text" name="phone" class="w-full border rounded px-3 py-2" required>
            </div>
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">Simpan Alamat</button>
        </form>
    </div>
</div>
@endsection
