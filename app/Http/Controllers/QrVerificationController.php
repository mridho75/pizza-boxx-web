<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class QrVerificationController extends Controller
{
    // Tampilkan form input kode QR
    public function showForm()
    {
        return view('qr.verify');
    }

    // Proses verifikasi QR
    public function verify(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
        ]);

        // Cari order berdasarkan kode unik di QR (bisa disesuaikan dengan format QR)
        $order = Order::where('qr_code_path', 'like', '%'.$request->qr_code.'%')->first();

        if (!$order) {
            return back()->with('error', 'QR Code tidak valid atau pesanan tidak ditemukan.');
        }

        if ($order->is_qr_verified) {
            return back()->with('error', 'Pesanan ini sudah diverifikasi.');
        }

        $order->is_qr_verified = true;
        $order->save();

        return back()->with('success', 'Pesanan berhasil diverifikasi!');
    }
}
