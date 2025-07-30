@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold text-red-500 mb-8 text-center">Keranjang Belanja Anda</h1>

        @if(session('success') && session('success') !== 'Keranjang berhasil diperbarui' && session('success') !== 'Keranjang berhasil dikosongkan.')
            <div class="bg-green-500 text-white p-3 rounded-lg mb-4 text-center">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-500 text-white p-3 rounded-lg mb-4 text-center">
                {{ session('error') }}
            </div>
        @endif

        @if(empty($cart))
            <div class="bg-white p-8 rounded-lg shadow-lg text-center"> {{-- Perubahan: bg-white --}}
                <p class="text-gray-600 text-lg mb-4">Keranjang Anda masih kosong.</p> {{-- Perubahan: text-gray-600 --}}
                <a href="{{ route('menu.index') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-full transition-colors">
                    Mulai Belanja Sekarang!
                </a>
            </div>
        @else
            <div class="bg-white p-6 rounded-2xl shadow-2xl border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 rounded-xl overflow-hidden">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Produk</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Harga Satuan</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Kuantitas</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Total Item</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($cart as $itemKey => $item)
                                <tr class="hover:bg-red-50 transition-all duration-150">
                                    <td class="px-6 py-6 whitespace-nowrap align-middle">
                                        <div class="flex items-center gap-5">
                                            <div class="flex-shrink-0 h-20 w-20 flex items-center justify-center">
                                                @if($item['image_path'])
                                                    <img class="h-20 w-20 rounded-xl object-cover border border-gray-200" src="{{ asset('storage/' . $item['image_path']) }}" alt="{{ $item['name'] }}">
                                                @else
                                                    <img class="h-20 w-20 rounded-xl object-cover border border-gray-200" src="{{ asset('images/pizza-boxx-logo.png') }}" alt="Default Image">
                                                @endif
                                            </div>
                                            <div>
                                                <div class="text-lg font-semibold text-gray-800">{{ $item['name'] }}</div>
                                                @if($item['size_option'])
                                                    <div class="text-xs text-gray-500">Ukuran: {{ $item['size_option']['name'] }}</div>
                                                @endif
                                                @if($item['crust_option'])
                                                    <div class="text-xs text-gray-500">Pinggiran: {{ $item['crust_option']['name'] }}</div>
                                                @endif
                                                @if(!empty($item['addons']))
                                                    <div class="text-xs text-gray-500">Tambahan:
                                                        @foreach($item['addons'] as $addon)
                                                            {{ $addon['name'] }}@if(!$loop->last), @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-6 whitespace-nowrap text-lg text-gray-800 align-middle">
                                        Rp {{ number_format($item['price_per_unit'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-6 whitespace-nowrap text-lg text-gray-800 text-center align-middle">
                                        <form action="{{ route('cart.update') }}" method="POST" class="flex items-center justify-center gap-4">
                                            @csrf
                                            <input type="hidden" name="item_key" value="{{ $itemKey }}">
                                            <button type="submit" name="quantity" value="{{ $item['quantity'] - 1 }}" class="bg-gray-200 hover:bg-red-600 hover:text-white text-gray-700 font-bold w-12 h-12 rounded-full flex items-center justify-center text-2xl transition-all">-</button>
                                            <span class="text-lg font-semibold text-gray-800 w-8 text-center">{{ $item['quantity'] }}</span>
                                            <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}" class="bg-gray-200 hover:bg-red-600 hover:text-white text-gray-700 font-bold w-12 h-12 rounded-full flex items-center justify-center text-2xl transition-all">+</button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-6 whitespace-nowrap text-xl text-red-600 font-bold text-right align-middle">
                                        Rp {{ number_format($item['total_price'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-6 whitespace-nowrap text-center align-middle">
                                        <form action="{{ route('cart.remove') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="item_key" value="{{ $itemKey }}">
                                            <button type="submit" class="bg-red-100/80 hover:bg-red-500 hover:text-white text-red-600 font-bold py-3 px-8 rounded-full transition-all duration-150 shadow-sm text-lg">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-8 border-t border-gray-100 pt-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                    <div class="text-gray-800 text-2xl font-bold flex items-center gap-2">
                        <svg class="h-7 w-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h8m-4-4v8"/></svg>
                        Total Keranjang:
                    </div>
                    <div class="text-red-600 text-4xl font-extrabold">Rp {{ number_format($cartTotal, 0, ',', '.') }}</div>
                </div>

                <div class="mt-8 flex flex-col md:flex-row justify-end gap-4">
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-gray-200 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded-full transition-all shadow-sm">Kosongkan Keranjang</button>
                    </form>
                    <a href="{{ route('checkout.index') }}" class="bg-gradient-to-r from-red-500 to-red-700 hover:from-red-600 hover:to-red-800 text-white font-bold py-2 px-8 rounded-full shadow-lg transition-all text-lg">Lanjutkan ke Checkout</a>
                </div>
            </div>
        @endif
    </div>
@endsection
