@extends('layouts.app')

@section('content')
<div class="container mx-auto py-10 px-2 md:px-0">
    <div class="mb-6">
        <a href="{{ route('pegawai.dashboard') }}" class="inline-flex items-center text-red-600 hover:text-red-800 font-semibold transition-colors duration-200">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            Kembali ke Dashboard
        </a>
    </div>
    <div class="bg-white rounded-2xl shadow-xl p-8 animate-fade-in-up">
        <h2 class="text-2xl font-bold mb-4 text-red-700">Detail Pengantaran #{{ $delivery->id }}</h2>
        <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <form action="{{ route('pegawai.deliveries.update', $delivery->id) }}" method="POST" class="space-y-3">
                    @csrf
                    <div><span class="font-semibold">Order:</span> #{{ $delivery->order_id }}</div>
                    <div>
                        <span class="font-semibold">Kurir:</span>
                        <select name="delivery_employee_id" class="rounded border-gray-300 focus:ring-red-400 focus:border-red-400">
                            @foreach(\App\Models\User::role('employee')->get() as $employee)
                                <option value="{{ $employee->id }}" @if($delivery->delivery_employee_id == $employee->id) selected @endif>{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <span class="font-semibold">Status:</span>
                        <select name="status" class="rounded border-gray-300 focus:ring-red-400 focus:border-red-400">
                            <option value="pending" @if($delivery->status=='pending') selected @endif>Pending</option>
                            <option value="on_delivery" @if($delivery->status=='on_delivery') selected @endif>On Delivery</option>
                            <option value="delivered" @if($delivery->status=='delivered') selected @endif>Delivered</option>
                            <option value="failed" @if($delivery->status=='failed') selected @endif>Failed</option>
                        </select>
                    </div>
                    <div>
                        <span class="font-semibold">Ditugaskan:</span>
                        <input type="datetime-local" name="assigned_at" value="{{ $delivery->assigned_at ? $delivery->assigned_at->format('Y-m-d\TH:i') : '' }}" class="rounded border-gray-300 focus:ring-red-400 focus:border-red-400" />
                    </div>
                    <div>
                        <span class="font-semibold">Diambil:</span>
                        <input type="datetime-local" name="picked_up_at" value="{{ $delivery->picked_up_at ? $delivery->picked_up_at->format('Y-m-d\TH:i') : '' }}" class="rounded border-gray-300 focus:ring-red-400 focus:border-red-400" />
                    </div>
                    <div>
                        <span class="font-semibold">Sampai:</span>
                        <input type="datetime-local" name="delivered_at" value="{{ $delivery->delivered_at ? $delivery->delivered_at->format('Y-m-d\TH:i') : '' }}" class="rounded border-gray-300 focus:ring-red-400 focus:border-red-400" />
                    </div>
                    <div>
                        <span class="font-semibold">Catatan:</span>
                        <textarea name="notes" class="rounded border-gray-300 focus:ring-red-400 focus:border-red-400 w-full">{{ $delivery->notes }}</textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded shadow font-semibold transition-all duration-200">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
