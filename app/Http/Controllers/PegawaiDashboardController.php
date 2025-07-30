<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Delivery;

class PegawaiDashboardController extends Controller
{
    public function index()
    {
        $orders = Order::orderByDesc('created_at')->get();
        $deliveries = Delivery::orderByDesc('created_at')->get();
        return view('pegawai.dashboard', compact('orders', 'deliveries'));
    }

    public function updateOrderStatus(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->status = $request->input('status');
        $order->save();
        return back()->with('success', 'Status pesanan berhasil diubah.');
    }

    public function updateDeliveryStatus(Request $request, $deliveryId)
    {
        $delivery = Delivery::findOrFail($deliveryId);
        $delivery->status = $request->input('status');
        $delivery->save();
        return back()->with('success', 'Status pengantaran berhasil diubah.');
    }
}
