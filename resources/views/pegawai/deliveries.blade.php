@extends('layouts.app')

@section('content')
<div class="container mx-auto py-10 px-2 md:px-0">
    <div class="bg-white rounded-2xl shadow-xl p-8 max-w-8xl mx-auto">
        <div class="flex items-center mb-6">
            <svg class="w-6 h-6 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6" /></svg>
            <h1 class="text-2xl font-extrabold text-red-600 tracking-tight">Daftar Pengantaran</h1>
            <div class="flex-1"></div>
            <a href="{{ route('pegawai.deliveries.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded shadow font-semibold transition-all duration-200">+ Buat Pengantaran</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-xl border border-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-3 text-center font-bold text-gray-700 uppercase tracking-wider">ID</th>
                        <th class="px-3 py-3 text-center font-bold text-gray-700 uppercase tracking-wider">Order</th>
                        <th class="px-3 py-3 text-center font-bold text-gray-700 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-3 py-3 text-center font-bold text-gray-700 uppercase tracking-wider">Telepon</th>
                        <th class="px-3 py-3 text-center font-bold text-gray-700 uppercase tracking-wider">Kurir</th>
                        <th class="px-3 py-3 text-center font-bold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-3 py-3 text-center font-bold text-gray-700 uppercase tracking-wider">Ditugaskan</th>
                        <th class="px-3 py-3 text-center font-bold text-gray-700 uppercase tracking-wider">Diambil</th>
                        <th class="px-3 py-3 text-center font-bold text-gray-700 uppercase tracking-wider">Sampai</th>
                        <th class="px-3 py-3 text-center font-bold text-gray-700 uppercase tracking-wider">Catatan</th>
                        <th class="px-3 py-3 text-center font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deliveries as $delivery)
                    <tr class="transition-colors duration-200 hover:bg-red-50 group border-b border-gray-100">
                        <td class="px-3 py-2 text-center font-semibold">{{ $delivery->id }}</td>
                        <td class="px-3 py-2 text-center">
                            <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">#{{ $delivery->order_id }}</span>
                        </td>
                        <td class="px-3 py-2 text-center">
                            {{ $delivery->order ? $delivery->order->customer_name : '-' }}
                        </td>
                        <td class="px-3 py-2 text-center">
                            {{ $delivery->order ? $delivery->order->customer_phone : '-' }}
                        </td>
                        <td class="px-3 py-2 text-center">
                            @if($delivery->deliveryEmployee)
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    {{ $delivery->deliveryEmployee->name }}
                                </span>
                            @else
                                <span class="inline-block px-2 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-center">
                            @php
                                $status = $delivery->status;
                                $badge = [
                                    'pending' => 'bg-yellow-100 text-yellow-700 border border-yellow-300',
                                    'on_delivery' => 'bg-blue-100 text-blue-700 border border-blue-300',
                                    'delivered' => 'bg-green-100 text-green-700 border border-green-300',
                                    'failed' => 'bg-red-100 text-red-700 border border-red-300',
                                    // fallback
                                    'default' => 'bg-gray-100 text-gray-700 border border-gray-300',
                                ];
                                $icon = '';
                                if ($status === 'pending') {
                                    $icon = '<svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2" /></svg>';
                                } elseif ($status === 'on_delivery') {
                                    $icon = '<svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" /></svg>';
                                } elseif ($status === 'delivered') {
                                    $icon = '<svg class="w-3 h-3 mr-1 inline text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
                                } elseif ($status === 'failed') {
                                    $icon = '<svg class="w-3 h-3 mr-1 inline text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>';
                                }
                                $badgeClass = $badge[$status] ?? $badge['default'];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $badgeClass }}">
                                {!! $icon !!}{{ ucfirst(str_replace('_', ' ', $status)) }}
                            </span>
                        </td>
                        <td class="px-3 py-2 text-center">{{ $delivery->assigned_at ? $delivery->assigned_at->format('d M Y H:i') : '-' }}</td>
                        <td class="px-3 py-2 text-center">{{ $delivery->picked_up_at ? $delivery->picked_up_at->format('d M Y H:i') : '-' }}</td>
                        <td class="px-3 py-2 text-center">{{ $delivery->delivered_at ? $delivery->delivered_at->format('d M Y H:i') : '-' }}</td>
                        <td class="px-3 py-2 text-center">
                            @if($delivery->notes)
                                <span title="{{ $delivery->notes }}">{{ Str::limit($delivery->notes, 30) }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-3 py-2 text-center">
                            <a href="{{ route('pegawai.deliveries.detail', $delivery->id) }}" class="px-2 py-1 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 rounded shadow text-xs font-semibold transition-all duration-200">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
