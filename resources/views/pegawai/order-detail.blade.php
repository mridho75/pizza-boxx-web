@extends('layouts.app')

@section('content')
<div class="container mx-auto py-10 px-2 md:px-0">
    <div class="mb-6">
        <a href="{{ url()->previous() }}" class="inline-flex items-center text-red-600 hover:text-red-800 font-semibold transition-colors duration-200">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            Kembali ke Dashboard
        </a>
    </div>
    <div class="bg-white rounded-2xl shadow-xl p-8 animate-fade-in-up">
        <h2 class="text-2xl font-bold mb-4 text-red-700">Detail Pesanan #{{ $order->id }}</h2>
        <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class="mb-2"><span class="font-semibold">Nama Pelanggan:</span> {{ $order->customer_name }}</div>
                <div class="mb-2"><span class="font-semibold">Email:</span> {{ $order->customer_email }}</div>
                <div class="mb-2"><span class="font-semibold">Telepon:</span> {{ $order->customer_phone }}</div>
                <div class="mb-2"><span class="font-semibold">Alamat Pengantaran:</span> {{ $order->delivery_address }}</div>
            </div>
            <div>
                <div class="mb-2"><span class="font-semibold">Status:</span> <span class="inline-block px-2 py-1 rounded-full text-xs font-bold @if($order->status=='pending') bg-yellow-100 text-yellow-700 @elseif($order->status=='processing') bg-blue-100 text-blue-700 @elseif($order->status=='completed') bg-green-100 text-green-700 @elseif($order->status=='cancelled') bg-red-100 text-red-700 @endif">{{ ucfirst($order->status) }}</span></div>
                <div class="mb-2"><span class="font-semibold">Metode Pembayaran:</span> {{ $order->payment_method }}</div>
                <div class="mb-2"><span class="font-semibold">Total:</span> <span class="text-lg font-bold text-red-600">Rp{{ number_format($order->total_amount,0,',','.') }}</span></div>
            </div>
        </div>
        <h3 class="text-lg font-semibold mt-6 mb-2">Item Pesanan</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-xl shadow border border-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Produk</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga Satuan</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Subtotal</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Opsi</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Addon</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr class="transition-colors duration-200 hover:bg-red-50 group">
                        <td class="px-4 py-2 font-semibold">{{ $item->product_name }}</td>
                        <td class="px-4 py-2">{{ $item->quantity }}</td>
                        <td class="px-4 py-2">Rp{{ number_format($item->unit_price,0,',','.') }}</td>
                        <td class="px-4 py-2">Rp{{ number_format($item->unit_price * $item->quantity,0,',','.') }}</td>
                        <td class="px-4 py-2">
                            @if(is_array($item->options))
                                @foreach($item->options as $opt)
                                    <span class="inline-block bg-gray-100 text-gray-700 rounded px-2 py-1 text-xs mr-1 mb-1">{{ $opt }}</span>
                                @endforeach
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            @if(is_array($item->addons))
                                @foreach($item->addons as $addon)
                                    <span class="inline-block bg-gray-100 text-gray-700 rounded px-2 py-1 text-xs mr-1 mb-1">{{ $addon }}</span>
                                @endforeach
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(16px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in-up { animation: fade-in 0.9s cubic-bezier(.4,0,.2,1) both; }
</style>
