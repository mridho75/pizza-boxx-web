<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ProductAddon;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Menampilkan isi keranjang belanja.
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        // Hitung total keseluruhan keranjang untuk ditampilkan di view
        $cartTotal = array_sum(array_column($cart, 'total_price'));
        return view('cart.index', compact('cart', 'cartTotal'));
    }

    /**
     * Menambahkan item ke keranjang belanja.
     */
    public function add(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);
        $sizeOptionId = $request->input('size_option_id');
        $crustOptionId = $request->input('crust_option_id');
        $addonIds = $request->input('addons', []);

        $product = Product::find($productId);

        if (!$product) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan!');
        }

        $item = [
            'product_id' => $product->id,
            'name' => $product->name,
            'image_path' => $product->image_path,
            'base_price' => $product->base_price,
            'quantity' => $quantity,
            'size_option' => null,
            'crust_option' => null,
            'addons' => [],
            // total_price akan dihitung setelah semua opsi/addon ditambahkan
            'total_price' => 0,
        ];

        $currentCalculatedPricePerUnit = $product->base_price;

        // Tambahkan opsi ukuran
        if ($sizeOptionId) {
            $sizeOption = ProductOption::find($sizeOptionId);
            if ($sizeOption && $sizeOption->type === 'Ukuran') {
                $item['size_option'] = [
                    'id' => $sizeOption->id,
                    'name' => $sizeOption->name,
                    'price_modifier' => $sizeOption->price_modifier,
                ];
                $currentCalculatedPricePerUnit += $sizeOption->price_modifier;
            }
        }

        // Tambahkan opsi pinggiran
        if ($crustOptionId) {
            $crustOption = ProductOption::find($crustOptionId);
            if ($crustOption && $crustOption->type === 'Pinggiran') {
                $item['crust_option'] = [
                    'id' => $crustOption->id,
                    'name' => $crustOption->name,
                    'price_modifier' => $crustOption->price_modifier,
                ];
                $currentCalculatedPricePerUnit += $crustOption->price_modifier;
            }
        }

        // Tambahkan add-ons
        if (!empty($addonIds)) {
            $addons = ProductAddon::whereIn('id', $addonIds)->where('product_id', $productId)->get();
            foreach ($addons as $addon) {
                $item['addons'][] = [
                    'id' => $addon->id,
                    'name' => $addon->name,
                    'price' => $addon->price,
                ];
                $currentCalculatedPricePerUnit += $addon->price;
            }
        }

        // Perbarui total_price item setelah semua opsi/addon (harga per unit)
        $item['total_price'] = $currentCalculatedPricePerUnit * $quantity;
        $item['price_per_unit'] = $currentCalculatedPricePerUnit; // Tambahkan harga per unit untuk update nanti

        // Dapatkan keranjang dari sesi
        $cart = session()->get('cart', []);

        // Buat kunci unik untuk item keranjang (berdasarkan product_id dan semua opsi yang dipilih)
        // Pastikan addonIds diurutkan agar kunci konsisten
        sort($addonIds);
        $itemKey = $productId . '-' . ($sizeOptionId ?? 'null') . '-' . ($crustOptionId ?? 'null') . '-' . implode(',', $addonIds);

        if (isset($cart[$itemKey])) {
            // Jika item sudah ada, perbarui kuantitasnya
            $cart[$itemKey]['quantity'] += $quantity;
            // Hitung ulang total_price untuk item yang sudah ada
            $cart[$itemKey]['total_price'] = $cart[$itemKey]['quantity'] * $cart[$itemKey]['price_per_unit'];
        } else {
            // Jika item belum ada, tambahkan sebagai item baru
            $cart[$itemKey] = $item;
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Memperbarui kuantitas item di keranjang.
     */
    public function update(Request $request)
    {
        $itemKey = $request->input('item_key');
        $newQuantity = $request->input('quantity');

        $cart = session()->get('cart', []);

        if (isset($cart[$itemKey])) {
            if ($newQuantity > 0) {
                // Gunakan price_per_unit yang sudah disimpan
                $baseItemPrice = $cart[$itemKey]['price_per_unit'];
                $cart[$itemKey]['quantity'] = $newQuantity;
                $cart[$itemKey]['total_price'] = $baseItemPrice * $newQuantity;
            } else {
                unset($cart[$itemKey]); // Hapus item jika kuantitas <= 0
            }
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Keranjang berhasil diperbarui');
    }

    /**
     * Menghapus item dari keranjang.
     */
    public function remove(Request $request)
    {
        $itemKey = $request->input('item_key');
        $cart = session()->get('cart', []);

        if (isset($cart[$itemKey])) {
            unset($cart[$itemKey]);
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    /**
     * Mengosongkan seluruh keranjang.
     */
    public function clear()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'Keranjang berhasil dikosongkan.');
    }
}
