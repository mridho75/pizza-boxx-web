@extends('layouts.app')

@section('content')
<div class="container mx-auto py-10 px-2 md:px-0">
    <h1 class="text-4xl font-extrabold mb-8 text-red-600 tracking-tight drop-shadow-lg animate-fade-in">Dashboard Pegawai</h1>
    @if(session('success'))
        <div class="bg-green-500 text-white p-3 rounded-lg mb-6 text-center shadow-md animate-fade-in">
            {{ session('success') }}
        </div>
    @endif
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        <div class="bg-white rounded-2xl shadow-xl p-6 transition-transform duration-300 hover:scale-[1.02] animate-fade-in-up">
            <h2 class="text-2xl font-bold mb-4 text-red-700 flex items-center gap-2">
                <svg class="w-6 h-6 text-red-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" /></svg>
                Daftar Pesanan
            </h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-xl shadow border border-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pelanggan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr class="transition-colors duration-200 hover:bg-red-50 group">
                            <td class="px-4 py-2 font-semibold">{{ $order->id }}</td>
                            <td class="px-4 py-2">{{ $order->customer_name }}</td>
                            <td class="px-4 py-2">
                                <span class="inline-block px-2 py-1 rounded-full text-xs font-bold
                                    @if($order->status=='pending') bg-yellow-100 text-yellow-700 @elseif($order->status=='processing') bg-blue-100 text-blue-700 @elseif($order->status=='completed') bg-green-100 text-green-700 @elseif($order->status=='cancelled') bg-red-100 text-red-700 @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 flex flex-col md:flex-row gap-2 items-start md:items-center">
                                <form action="{{ route('pegawai.orders.update', $order->id) }}" method="POST" class="inline">
                                    @csrf
                                    <select name="status" class="rounded border-gray-300 focus:ring-red-400 focus:border-red-400 transition-all duration-200">
                                        <option value="pending" @if($order->status=='pending') selected @endif>Pending</option>
                                        <option value="processing" @if($order->status=='processing') selected @endif>Processing</option>
                                        <option value="completed" @if($order->status=='completed') selected @endif>Selesai</option>
                                        <option value="cancelled" @if($order->status=='cancelled') selected @endif>Dibatalkan</option>
                                    </select>
                                    <button type="submit" class="ml-2 px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">Ubah</button>
                                </form>
                                <a href="{{ route('pegawai.orders.detail', $order->id) }}" class="ml-0 md:ml-2 px-3 py-1 bg-red-100 hover:bg-red-200 text-red-700 rounded shadow transition-all duration-200 text-xs font-semibold flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0A9 9 0 11 3 12a9 9 0 0118 0z" /></svg>
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-xl p-6 transition-transform duration-300 hover:scale-[1.02] animate-fade-in-up">
            <h2 class="text-2xl font-bold mb-4 text-red-700 flex items-center gap-2">
                <svg class="w-6 h-6 text-red-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6" /></svg>
                Daftar Pengantaran
            </h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-xl shadow border border-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kurir</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deliveries as $delivery)
                        <tr class="transition-colors duration-200 hover:bg-red-50 group">
                            <td class="px-4 py-2 font-semibold">{{ $delivery->id }}</td>
                            <td class="px-4 py-2">{{ $delivery->courier_name ?? '-' }}</td>
                            <td class="px-4 py-2">
                                <span class="inline-block px-2 py-1 rounded-full text-xs font-bold
                                    @if($delivery->status=='pending') bg-yellow-100 text-yellow-700 @elseif($delivery->status=='on_delivery') bg-blue-100 text-blue-700 @elseif($delivery->status=='delivered') bg-green-100 text-green-700 @elseif($delivery->status=='cancelled') bg-red-100 text-red-700 @endif">
                                    {{ ucfirst($delivery->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                <form action="{{ route('pegawai.deliveries.update', $delivery->id) }}" method="POST" class="inline">
                                    @csrf
                                    <select name="status" class="rounded border-gray-300 focus:ring-red-400 focus:border-red-400 transition-all duration-200">
                                        <option value="pending" @if($delivery->status=='pending') selected @endif>Pending</option>
                                        <option value="on_delivery" @if($delivery->status=='on_delivery') selected @endif>Dalam Pengantaran</option>
                                        <option value="delivered" @if($delivery->status=='delivered') selected @endif>Terkirim</option>
                                        <option value="cancelled" @if($delivery->status=='cancelled') selected @endif>Dibatalkan</option>
                                    </select>
                                    <button type="submit" class="ml-2 px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">Ubah</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(16px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in { animation: fade-in 0.7s cubic-bezier(.4,0,.2,1) both; }
.animate-fade-in-up { animation: fade-in 0.9s cubic-bezier(.4,0,.2,1) both; }
</style>
@endsection
