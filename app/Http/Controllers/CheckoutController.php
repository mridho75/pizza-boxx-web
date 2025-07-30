<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Location;
use App\Models\Promo;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log; // Import Log facade
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class CheckoutController extends Controller
{
    /**
     * Menampilkan halaman checkout.
     */
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda kosong, tidak bisa checkout.');
        }

        $cartTotal = array_sum(array_column($cart, 'total_price'));
        $locations = Location::all();
        $user = auth()->user();

        return view('checkout.index', compact('cart', 'cartTotal', 'locations', 'user'));
    }

    /**
     * Memproses pesanan dari halaman checkout.
     */
    public function process(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda kosong, tidak bisa checkout.');
        }

        // Validasi data input dari form checkout
        $validatedData = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255', // <--- PERUBAHAN DI SINI: max:255
            'customer_phone' => 'required|string|max:20',
            'order_type' => 'required|in:delivery,pickup',
            'payment_method' => 'required|in:online,cash_on_delivery,card_on_pickup',
            'location_id' => 'required|exists:locations,id',
            'delivery_address' => 'required_if:order_type,delivery|nullable|string|max:500', // <--- PERUBAHAN DI SINI: max:500
            'delivery_notes' => 'nullable|string|max:500', // <--- PERUBAHAN DI SINI: max:500
            'promo_code' => 'nullable|string|exists:promos,code',
            'subtotal_amount' => 'required|numeric|min:0',
            'discount_amount' => 'required|numeric|min:0',
            'delivery_fee' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $recalculatedSubtotal = 0;
            foreach ($cart as $item) {
                $recalculatedSubtotal += $item['price_per_unit'] * $item['quantity'];
            }

            $discountAmount = 0;
            $promoId = null;
            if (!empty($validatedData['promo_code'])) {
                $promo = Promo::where('code', $validatedData['promo_code'])
                              ->where('is_active', true)
                              ->where(function($query) {
                                  $query->whereNull('start_date')
                                        ->orWhere('start_date', '<=', now());
                              })
                              ->where(function($query) {
                                  $query->whereNull('end_date')
                                        ->orWhere('end_date', '>=', now());
                              })
                              ->first();

                if ($promo && $recalculatedSubtotal >= ($promo->min_order_amount ?? 0)) {
                    if ($promo->type === 'percentage') {
                        $discountAmount = $recalculatedSubtotal * ($promo->value / 100);
                    } elseif ($promo->type === 'fixed_amount') {
                        $discountAmount = $promo->value;
                    }
                    $promoId = $promo->id;
                    if ($promo->usage_limit !== null) {
                        $promo->increment('uses');
                    }
                } else {
                    DB::rollBack();
                    return redirect()->back()->withInput()->with('error', 'Kode promo tidak valid atau tidak memenuhi syarat!');
                }
            }

            $deliveryFee = $validatedData['delivery_fee'];
            $recalculatedTotal = $recalculatedSubtotal - $discountAmount + $deliveryFee;

            if (abs($recalculatedSubtotal - $validatedData['subtotal_amount']) > 0.01 ||
                abs($discountAmount - $validatedData['discount_amount']) > 0.01 ||
                abs($recalculatedTotal - $validatedData['total_amount']) > 0.01) {
                DB::rollBack();
                return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan perhitungan harga. Mohon coba lagi.');
            }

            $order = Order::create([
                'user_id' => auth()->id(),
                'location_id' => $validatedData['location_id'],
                'customer_name' => $validatedData['customer_name'],
                'customer_email' => $validatedData['customer_email'],
                'customer_phone' => $validatedData['customer_phone'],
                'order_type' => $validatedData['order_type'],
                'payment_method' => $validatedData['payment_method'],
                'status' => 'pending',
                'delivery_address' => $validatedData['delivery_address'],
                'delivery_notes' => $validatedData['delivery_notes'],
                'subtotal_amount' => $recalculatedSubtotal,
                'discount_amount' => $discountAmount,
                'delivery_fee' => $deliveryFee,
                'total_amount' => $recalculatedTotal,
                'promo_id' => $promoId,
                'delivery_employee_id' => null,
            ]);

            // Generate QR code untuk takeaway (pickup)
            if ($order->order_type === 'pickup') {
                $qrData = 'ORDER-' . $order->id . '-' . strtoupper(uniqid());
                $qrFileName = 'qr_codes/order_' . $order->id . '_' . time() . '.png';
                $qrImage = QrCode::format('png')->size(300)->margin(2)->generate($qrData);
                Storage::disk('public')->put($qrFileName, $qrImage);
                $order->qr_code_path = $qrFileName;
                $order->is_qr_verified = false;
                $order->save();
            }

            foreach ($cart as $itemKey => $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem['product_id'],
                    'product_name' => $cartItem['name'],
                    'quantity' => $cartItem['quantity'],
                    'unit_price' => $cartItem['price_per_unit'],
                    'options' => $cartItem['size_option'] ? array_merge($cartItem['size_option'], ($cartItem['crust_option'] ?? [])) : ($cartItem['crust_option'] ?? null),
                    'addons' => $cartItem['addons'],
                ]);
            }

            DB::commit();
            session()->forget('cart');

            return redirect()->route('checkout.success', ['order_id' => $order->id])->with('success', 'Pesanan Anda berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout failed: ' . $e->getMessage(), ['exception' => $e]); // Menggunakan Log facade
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memproses pesanan Anda. Mohon coba lagi. (Error: ' . $e->getMessage() . ')');
        }
    }

    /**
     * Menampilkan halaman sukses checkout.
     */
    public function success(Request $request)
    {
        $orderId = $request->query('order_id');
        $order = Order::with(['orderItems.product', 'location', 'promo', 'user'])->find($orderId);

        if (!$order || (auth()->check() && $order->user_id !== auth()->id() && !auth()->user()->hasAnyRole(['admin', 'employee']))) {
            // Jika order tidak ditemukan atau user tidak punya akses
            return redirect()->route('home')->with('error', 'Pesanan tidak ditemukan atau Anda tidak memiliki akses.');
        }
        // Jika user tidak login dan order_id tidak ada atau tidak valid, redirect ke home
        if (!auth()->check() && !$order) {
            return redirect()->route('home')->with('error', 'Pesanan tidak ditemukan.');
        }

        return view('checkout.success', compact('order'));
    }

    /**
     * API untuk validasi kode promo.
     */
    public function validatePromo(Request $request)
    {
        $promoCode = $request->input('promo_code');
        $subtotal = $request->input('subtotal');

        $promo = Promo::where('code', $promoCode)
                      ->where('is_active', true)
                      ->where(function($query) {
                          $query->whereNull('start_date')
                                ->orWhere('start_date', '<=', now());
                      })
                      ->where(function($query) {
                          $query->whereNull('end_date')
                                ->orWhere('end_date', '>=', now());
                      })
                      ->first();

        if (!$promo) {
            return response()->json([
                'success' => false,
                'message' => 'Kode promo tidak ditemukan atau tidak aktif.'
            ]);
        }

        if ($subtotal < ($promo->min_order_amount ?? 0)) {
            return response()->json([
                'success' => false,
                'message' => 'Promo ini berlaku untuk minimal belanja Rp ' . number_format($promo->min_order_amount, 0, ',', '.') . '.'
            ]);
        }

        $discountAmount = 0;
        if ($promo->type === 'percentage') {
            $discountAmount = $subtotal * ($promo->value / 100);
        } elseif ($promo->type === 'fixed_amount') {
            $discountAmount = $promo->value;
        }

        return response()->json([
            'success' => true,
            'message' => 'Promo berhasil diterapkan!',
            'discount_amount' => round($discountAmount, 2)
        ]);
    }
}
