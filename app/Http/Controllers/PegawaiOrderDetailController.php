<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class PegawaiOrderDetailController extends Controller
{
    public function show($orderId)
    {
        $order = Order::with(['orderItems'])->findOrFail($orderId);
        return view('pegawai.order-detail', compact('order'));
    }
}
