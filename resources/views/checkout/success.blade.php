@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 animate-fade-in">
        <div class="bg-white p-8 rounded-xl shadow-xl max-w-3xl mx-auto border border-gray-100 transform transition-all duration-300 hover:shadow-2xl">
            <!-- Success Confirmation -->
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-green-600 mb-3">Pesanan Berhasil!</h1>
                <p class="text-gray-700 text-lg mb-6">Terima kasih telah memesan di Pizza Boxx. Berikut detail pesanan Anda:</p>
                <div class="w-full bg-gradient-to-r from-green-100 to-green-50 p-3 rounded-lg border-l-4 border-green-500 mb-6">
                    <p class="text-green-700 font-medium">Nomor Pesanan: <span class="font-bold">#{{ $order->id }}</span></p>
                </div>
            </div>

            <!-- Status Messages -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 animate-slide-up">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 animate-slide-up">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Order Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Customer Information -->
                <div class="bg-gray-50 p-5 rounded-lg border border-gray-200">
                    <h2 class="text-xl font-bold text-red-600 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Informasi Pelanggan
                    </h2>
                    <div class="space-y-2">
                        <p class="text-gray-700"><span class="font-semibold">Nama:</span> {{ $order->customer_name }}</p>
                        <p class="text-gray-700"><span class="font-semibold">Telepon:</span> {{ $order->customer_phone }}</p>
                        <p class="text-gray-700"><span class="font-semibold">Lokasi Toko:</span> {{ $order->location->name }}</p>
                        <p class="text-gray-700"><span class="font-semibold">Alamat Toko:</span> {{ $order->location->address }}</p>
                    </div>
                </div>

                <!-- Order Information -->
                <div class="bg-gray-50 p-5 rounded-lg border border-gray-200">
                    <h2 class="text-xl font-bold text-red-600 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Detail Pesanan
                    </h2>
                    <div class="space-y-2">
                        <p class="text-gray-700"><span class="font-semibold">Tipe:</span> {{ ucfirst($order->order_type) }}</p>
                        @if($order->order_type === 'delivery')
                            <p class="text-gray-700"><span class="font-semibold">Alamat:</span> {{ $order->delivery_address }}</p>
                            @if($order->delivery_notes)
                                <p class="text-gray-700"><span class="font-semibold">Catatan:</span> {{ $order->delivery_notes }}</p>
                            @endif
                        @endif
                        <p class="text-gray-700"><span class="font-semibold">Pembayaran:</span> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                        <p class="text-gray-700"><span class="font-semibold">Status:</span> <span class="px-2 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">{{ ucfirst($order->status) }}</span></p>
                        <p class="text-gray-700"><span class="font-semibold">Waktu:</span> {{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 mb-8">
                <h2 class="text-xl font-bold text-red-600 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4z" />
                    </svg>
                    Item Pesanan
                </h2>
                <div class="divide-y divide-gray-200">
                    @foreach($order->orderItems as $item)
                        <div class="py-3 flex justify-between items-start">
                            <div>
                                <p class="font-medium text-gray-800">
                                    {{ $item->quantity }}x {{ $item->product_name }}
                                    <span class="text-gray-600">(Rp {{ number_format($item->unit_price, 0, ',', '.') }})</span>
                                </p>
                                @if($item->options)
                                    <div class="text-sm text-gray-500 mt-1">
                                        @foreach($item->options as $option)
                                            @if(isset($option['name']))
                                                <span class="inline-block bg-gray-100 rounded px-2 py-1 mr-1 mb-1">
                                                    {{ $option['name'] }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                                @if(!empty($item->addons))
                                    <div class="text-sm text-gray-500 mt-1">
                                        <span class="font-medium">Tambahan:</span>
                                        @foreach($item->addons as $addon)
                                            <span class="inline-block bg-gray-100 rounded px-2 py-1 mr-1 mb-1">
                                                {{ $addon['name'] }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <p class="font-semibold text-gray-800">Rp {{ number_format($item->quantity * $item->unit_price, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Summary -->
            <div class="bg-gray-50 p-5 rounded-lg border border-gray-200">
                <h2 class="text-xl font-bold text-red-600 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                    </svg>
                    Ringkasan Pembayaran
                </h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-700">Subtotal</span>
                        <span class="font-medium">Rp {{ number_format($order->subtotal_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-700">Diskon</span>
                        <span class="font-medium text-green-600">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-700">Biaya Pengiriman</span>
                        <span class="font-medium">Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</span>
                    </div>
                    <div class="border-t border-gray-200 pt-3 mt-3 flex justify-between">
                        <span class="text-lg font-bold text-gray-800">Total Pembayaran</span>
                        <span class="text-xl font-bold text-red-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('home') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg text-lg transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Kembali ke Beranda
                </a>
                <a href="{{ route('user.dashboard') }}" class="bg-white border border-red-600 text-red-600 hover:bg-red-50 font-bold py-3 px-6 rounded-lg text-lg transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Lihat Pesanan Saya
                </a>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .animate-fade-in {
        animation: fadeIn 0.6s ease-out forwards;
    }
    .animate-slide-up {
        animation: slideUp 0.5s ease-out forwards;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush
