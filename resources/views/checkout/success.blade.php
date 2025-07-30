@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white p-8 rounded-lg shadow-lg text-center max-w-2xl mx-auto">
            <h1 class="text-4xl font-bold text-green-600 mb-4">Pesanan Berhasil Dikonfirmasi!</h1>
            <p class="text-gray-700 text-lg mb-6">Terima kasih atas pesanan Anda. Detail pesanan Anda adalah sebagai berikut:</p>

            @if(session('success'))
                <div class="bg-green-500 text-white p-3 rounded-lg mb-4 text-center">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-500 text-white p-3 rounded-lg mb-4 text-center">
                    {{ session('error') }}
                </div>
            @endif

            <div class="border-t border-gray-200 pt-6 mt-6 text-left">
                <h2 class="text-2xl font-bold text-red-600 mb-4">Detail Pesanan #{{ $order->id }}</h2>
                <p class="text-gray-700 mb-2"><strong>Nama Pelanggan:</strong> {{ $order->customer_name }}</p>
                <p class="text-gray-700 mb-2"><strong>Nomor Telepon:</strong> {{ $order->customer_phone }}</p>
                <p class="text-gray-700 mb-2"><strong>Lokasi Toko:</strong> {{ $order->location->name }} ({{ $order->location->address }})</p>
                <p class="text-gray-700 mb-2"><strong>Tipe Pesanan:</strong> {{ ucfirst($order->order_type) }}</p>
                @if($order->order_type === 'delivery')
                    <p class="text-gray-700 mb-2"><strong>Alamat Pengiriman:</strong> {{ $order->delivery_address }}</p>
                    @if($order->delivery_notes)
                        <p class="text-gray-700 mb-2"><strong>Catatan:</strong> {{ $order->delivery_notes }}</p>
                    @endif
                @endif
                <p class="text-gray-700 mb-2"><strong>Metode Pembayaran:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                <p class="text-gray-700 mb-2"><strong>Status:</strong> <span class="font-semibold text-red-600">{{ ucfirst($order->status) }}</span></p>
                <p class="text-gray-700 mb-2"><strong>Waktu Pesan:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>

            <div class="border-t border-gray-200 pt-6 mt-6 text-left">
                <h2 class="text-2xl font-bold text-red-600 mb-4">Item Pesanan</h2>
                <ul class="list-disc list-inside text-gray-700 mb-4">
                    @foreach($order->orderItems as $item)
                        <li>
                            {{ $item->quantity }}x {{ $item->product_name }} (Rp {{ number_format($item->unit_price, 0, ',', '.') }})
                            @if($item->options)
                                @foreach($item->options as $option)
                                    @if(isset($option['name'])) - {{ $option['name'] }} @endif
                                @endforeach
                            @endif
                            @if(!empty($item->addons))
                                (+ Tambahan:
                                @foreach($item->addons as $addon)
                                    {{ $addon['name'] }}@if(!$loop->last), @endif
                                @endforeach)
                            @endif
                            <span class="font-semibold ml-2">Rp {{ number_format($item->quantity * $item->unit_price, 0, ',', '.') }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="border-t border-gray-200 pt-6 mt-6 text-right">
                <p class="text-lg text-gray-700 mb-2">Subtotal: <span class="font-semibold">Rp {{ number_format($order->subtotal_amount, 0, ',', '.') }}</span></p>
                <p class="text-lg text-gray-700 mb-2">Diskon: <span class="font-semibold text-green-600">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span></p>
                <p class="text-lg text-gray-700 mb-2">Biaya Pengiriman: <span class="font-semibold">Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</span></p>
                <p class="text-2xl font-bold text-red-600">Total Pembayaran: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
            </div>

            <div class="mt-8">
                <a href="{{ route('home') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-full text-xl transition-colors">
                    Kembali ke Halaman Utama
                </a>
            </div>
        </div>
    </div>
@endsection