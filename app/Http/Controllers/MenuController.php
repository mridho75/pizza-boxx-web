<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\Location; // <--- TAMBAHKAN INI

class MenuController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua kategori
        $categories = Category::all();

        // Ambil produk berdasarkan kategori yang dipilih, atau semua produk jika tidak ada kategori dipilih
        $selectedCategory = $request->input('category');

        $productsQuery = Product::with('category');

        if ($selectedCategory) {
            $productsQuery->where('category_id', $selectedCategory);
        }

        $products = $productsQuery->where('is_available', true)->paginate(12);

        // --- TAMBAHAN UNTUK CEK LOKASI ---
        // Dapatkan lokasi cabang Sukahati (sesuaikan dengan nama lokasi yang Anda masukkan di admin)
        $sukahatiBranch = Location::where('name', 'Pizza Boxx, Sukahati Cibinong')->first(); // <--- SESUAIKAN NAMA LOKASI DI SINI

        // Siapkan data lokasi untuk JavaScript
        $branchLocationData = null;
        if ($sukahatiBranch && $sukahatiBranch->latitude && $sukahatiBranch->longitude && $sukahatiBranch->delivery_radius_km) {
            $branchLocationData = [
                'latitude' => (float)$sukahatiBranch->latitude,
                'longitude' => (float)$sukahatiBranch->longitude,
                'radius_km' => (int)$sukahatiBranch->delivery_radius_km,
            ];
        }
        // --- AKHIR TAMBAHAN ---

        return view('menu', compact('categories', 'products', 'selectedCategory', 'branchLocationData')); // <--- KIRIM branchLocationData
    }

    // Fungsi untuk halaman detail produk
    public function show(Product $product)
    {
        $productOptions = $product->options;
        $productAddons = $product->addons;

        $universalCrusts = ProductOption::where('type', 'Pinggiran')->get();

        $product->load(['options', 'addons']);

        return view('product-detail', compact('product', 'universalCrusts'));
    }
}
