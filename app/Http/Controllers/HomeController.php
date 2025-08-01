<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil 4 produk terpopuler (bisa disesuaikan field popularitasnya)
        $popularPizzas = Product::where('is_available', true)
            ->orderByDesc('created_at') // Ganti dengan field popularitas yang sesuai jika ada
            ->take(4)
            ->get();

        return view('home', compact('popularPizzas'));
    }
}
