<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\User;

class PegawaiDeliveryController extends Controller
{
    public function index()
    {
        $deliveries = Delivery::with(['order', 'deliveryEmployee'])->orderByDesc('created_at')->get();
        return view('pegawai.deliveries', compact('deliveries'));
    }

    public function create()
    {
        $orders = Order::where('order_type', 'delivery')->get();
        $employees = User::role('employee')->get();
        return view('pegawai.deliveries-create', compact('orders', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'delivery_employee_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,on_delivery,delivered,failed',
            'assigned_at' => 'nullable|date',
            'picked_up_at' => 'nullable|date',
            'delivered_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        Delivery::create($validated);
        return redirect()->route('pegawai.deliveries.index')->with('success', 'Pengantaran berhasil dibuat.');
    }

    public function detail($id)
    {
        $delivery = Delivery::with(['order', 'deliveryEmployee'])->findOrFail($id);
        return view('pegawai.delivery-detail', compact('delivery'));
    }

    public function update(Request $request, $id)
    {
        $delivery = Delivery::findOrFail($id);
        $validated = $request->validate([
            'delivery_employee_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,on_delivery,delivered,failed',
            'assigned_at' => 'nullable|date',
            'picked_up_at' => 'nullable|date',
            'delivered_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        $delivery->update($validated);
        return redirect()->route('pegawai.deliveries.detail', $delivery->id)->with('success', 'Pengantaran berhasil diupdate.');
    }
}
