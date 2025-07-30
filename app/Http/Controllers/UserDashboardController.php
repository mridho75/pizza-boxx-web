<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class UserDashboardController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();
        return view('user.dashboard', compact('orders'));
    }
}
