@extends('layouts.app')

@section('content')
<div class="container mx-auto py-10 px-2 md:px-0 max-w-5xl">
    <h2 class="text-3xl font-bold mb-8 text-red-600 text-center">Dashboard Pelanggan</h2>
    <div class="bg-white rounded-2xl shadow-xl p-6 md:p-10">
        <h3 class="text-xl font-semibold mb-6 text-gray-800">Riwayat Pesanan Anda</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-xl divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase">Kode Pesanan</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase">QR Code</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr class="hover:bg-red-50 transition-all">
                        <td class="px-6 py-4 align-middle">{{ $order->created_at->format('d-m-Y H:i') }}</td>
                        <td class="px-6 py-4 align-middle font-mono text-base">{{ $order->order_code ?? $order->id }}</td>
                        <td class="px-6 py-4 align-middle text-lg text-red-600 font-bold">Rp {{ number_format($order->total ?? $order->total_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 align-middle">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'on_delivery' => 'bg-blue-100 text-blue-800',
                                    'delivered' => 'bg-green-100 text-green-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'failed' => 'bg-red-100 text-red-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                ];
                                $status = strtolower($order->status);
                                $badgeClass = $statusColors[$status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 align-middle">
                            @if(($order->order_type === 'takeaway' || $order->order_type === 'pickup') && $order->qr_code_path)
                                <button onclick="showQrModal('{{ asset('storage/' . $order->qr_code_path) }}')" class="bg-gray-100 hover:bg-red-100 text-gray-700 font-semibold px-4 py-2 rounded-lg shadow-sm transition-all">Lihat QR</button>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada pesanan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal QR Code --}}
    <div id="qrModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
        <div class="bg-white rounded-2xl shadow-2xl p-8 flex flex-col items-center relative max-w-xs">
            <button onclick="closeQrModal()" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 text-2xl">&times;</button>
            <img id="qrModalImg" src="" alt="QR Code" class="h-48 w-48 object-contain mb-4">
            <span class="text-gray-700 text-sm">Tunjukkan QR ini ke kasir/pegawai saat pengambilan pesanan.</span>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showQrModal(src) {
        document.getElementById('qrModalImg').src = src;
        document.getElementById('qrModal').classList.remove('hidden');
    }
    function closeQrModal() {
        document.getElementById('qrModal').classList.add('hidden');
        document.getElementById('qrModalImg').src = '';
    }
</script>
@endpush
@endsection
