@extends('layouts.app')

@section('content')
<div class="container mx-auto py-10 px-2 md:px-0">
    <h1 class="text-3xl font-extrabold mb-8 text-red-600 tracking-tight drop-shadow-lg">Buat Pengantaran</h1>
    <a href="{{ route('pegawai.deliveries.index') }}" class="inline-block mb-6 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded shadow font-semibold transition-all duration-200"><i class="fa fa-arrow-left mr-2"></i>Kembali</a>
    <form action="{{ route('pegawai.deliveries.store') }}" method="POST" class="bg-white rounded-2xl shadow-xl p-8 max-w-xl mx-auto">
        @csrf
        <div class="mb-4">
            <label class="block font-semibold mb-1">Order</label>
            <select name="order_id" class="w-full rounded border-gray-300 focus:ring-red-400 focus:border-red-400">
                <option value="">Pilih Order</option>
                @foreach($orders as $order)
                    <option value="{{ $order->id }}">#{{ $order->id }} - {{ $order->customer_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Kurir</label>
            <select name="delivery_employee_id" class="w-full rounded border-gray-300 focus:ring-red-400 focus:border-red-400">
                <option value="">Pilih Kurir</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Status</label>
            <select name="status" class="w-full rounded border-gray-300 focus:ring-red-400 focus:border-red-400">
                <option value="pending">Pending</option>
                <option value="on_delivery">On Delivery</option>
                <option value="delivered">Delivered</option>
                <option value="failed">Failed</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Waktu Ditugaskan</label>
            <input type="datetime-local" name="assigned_at" class="w-full rounded border-gray-300 focus:ring-red-400 focus:border-red-400" />
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Waktu Diambil</label>
            <input type="datetime-local" name="picked_up_at" class="w-full rounded border-gray-300 focus:ring-red-400 focus:border-red-400" />
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Waktu Sampai</label>
            <input type="datetime-local" name="delivered_at" class="w-full rounded border-gray-300 focus:ring-red-400 focus:border-red-400" />
        </div>
        <div class="mb-6">
            <label class="block font-semibold mb-1">Catatan Kurir</label>
            <textarea name="notes" class="w-full rounded border-gray-300 focus:ring-red-400 focus:border-red-400"></textarea>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded shadow font-semibold transition-all duration-200">Simpan</button>
        </div>
    </form>
</div>
@endsection
