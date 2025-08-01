@extends('layouts.app')

@section('content')
<div class="container mx-auto py-10 px-2 md:px-0">
    {{-- Judul Dashboard Pegawai dipindah ke sidebar/layout --}}
    @if(session('success'))
        <div class="bg-green-500 text-white p-3 rounded-lg mb-6 text-center shadow-md animate-fade-in">
            {{ session('success') }}
        </div>
    @endif
    <div class="bg-white rounded-2xl shadow-xl p-6 transition-transform duration-300 hover:scale-[1.02] animate-fade-in-up">
        <h2 class="text-2xl font-bold mb-4 text-red-700 flex items-center gap-2">
            <svg class="w-6 h-6 text-red-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" /></svg>
            Daftar Pesanan
        </h2>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-xl shadow border border-gray-200 text-sm">
                <thead class="bg-gray-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-3 py-3 text-center font-bold text-gray-700 uppercase tracking-wider">ID</th>
                        <th class="px-3 py-3 text-left font-bold text-gray-700 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-3 py-3 text-left font-bold text-gray-700 uppercase tracking-wider">Waktu Order</th>
                        <th class="px-3 py-3 text-center font-bold text-gray-700 uppercase tracking-wider">Tipe</th>
                        <th class="px-3 py-3 text-center font-bold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-3 py-3 text-center font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr class="transition-colors duration-200 hover:bg-red-50 group">
                        <td class="px-3 py-2 text-center font-semibold">{{ $order->id }}</td>
                        <td class="px-3 py-2 text-left">{{ $order->customer_name }}</td>
                        <td class="px-3 py-2 text-left text-gray-500">{{ $order->created_at->format('d M Y H:i') }} WIB</td>
                        <td class="px-3 py-2 text-center">
                            <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold @if($order->order_type=='pickup') bg-green-100 text-green-700 @else bg-blue-100 text-blue-700 @endif">
                                {{ $order->order_type == 'pickup' ? 'Takeaway' : 'Delivery' }}
                            </span>
                        </td>
                        <td class="px-3 py-2 text-center">
                            @php
                                $status = $order->status;
                                $badge = [
                                    'pending' => 'bg-yellow-100 text-yellow-700 border border-yellow-300',
                                    'processing' => 'bg-blue-100 text-blue-700 border border-blue-300',
                                    'preparing' => 'bg-blue-100 text-blue-700 border border-blue-300',
                                    'ready_for_delivery' => 'bg-blue-100 text-blue-700 border border-blue-300',
                                    'on_delivery' => 'bg-blue-100 text-blue-700 border border-blue-300',
                                    'delivered' => 'bg-green-100 text-green-700 border border-green-300',
                                    'completed' => 'bg-green-100 text-green-700 border border-green-300',
                                    'cancelled' => 'bg-red-100 text-red-700 border border-red-300',
                                    'failed' => 'bg-red-100 text-red-700 border border-red-300',
                                    'refunded' => 'bg-gray-100 text-gray-700 border border-gray-300',
                                    // fallback
                                    'default' => 'bg-gray-100 text-gray-700 border border-gray-300',
                                ];
                                $icon = '';
                                if ($status === 'pending') {
                                    $icon = '<svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2" /></svg>';
                                } elseif (in_array($status, ['processing','preparing','ready_for_delivery','on_delivery'])) {
                                    $icon = '<svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" /></svg>';
                                } elseif (in_array($status, ['completed','delivered'])) {
                                    $icon = '<svg class="w-3 h-3 mr-1 inline text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
                                } elseif (in_array($status, ['cancelled','failed'])) {
                                    $icon = '<svg class="w-3 h-3 mr-1 inline text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>';
                                }
                                $badgeClass = $badge[$status] ?? $badge['default'];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $badgeClass }}">
                                {!! $icon !!}{{ ucfirst(str_replace('_', ' ', $status)) }}
                            </span>
                        </td>
                        <td class="px-3 py-2 text-center flex flex-col md:flex-row gap-2 items-center justify-center">
                            <a href="{{ route('pegawai.orders.detail', $order->id) }}" class="px-2 py-1 bg-red-100 hover:bg-red-200 text-red-700 rounded shadow transition-all duration-200 text-xs font-semibold flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0A9 9 0 11 3 12a9 9 0 0118 0z" /></svg>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
