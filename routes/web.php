<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserAddressController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Semua route utama aplikasi
*/

// =================== AUTH ===================
// Login & Register Customer
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Login Pegawai
Route::get('/login/pegawai', [AuthController::class, 'showPegawaiLoginForm'])->name('pegawai.login');
Route::post('/login/pegawai', [AuthController::class, 'pegawaiLogin']);

// =================== HALAMAN UTAMA ===================
Route::get('/', [HomeController::class, 'index'])->name('home');

// =================== MENU ===================
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/menu/{product}', [MenuController::class, 'show'])->name('menu.show');

// =================== KERANJANG ===================
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('cart.clear');
});

// =================== CHECKOUT ===================
Route::prefix('checkout')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::post('/api/validate-promo', [CheckoutController::class, 'validatePromo']);
});

// =================== DASHBOARD PELANGGAN ===================
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
});

// =================== DASHBOARD PEGAWAI ===================
Route::middleware(['auth:employee', 'role:admin,employee'])->prefix('pegawai')->group(function () {
    // Dashboard Pegawai
    Route::get('/dashboard', [App\Http\Controllers\PegawaiDashboardController::class, 'index'])->name('pegawai.dashboard');
    // Dummy Orders Pegawai agar sidebar tidak error
    Route::get('/orders', function() {
        return view('pegawai.orders-dummy');
    })->name('pegawai.orders');
    // Dummy Delivery Pegawai agar sidebar tidak error
    Route::get('/delivery', function() {
        return view('pegawai.delivery-dummy');
    })->name('pegawai.delivery');
    // Order Detail & Update
    Route::get('/orders/{order}/detail', [App\Http\Controllers\PegawaiOrderDetailController::class, 'show'])->name('pegawai.orders.detail');
    Route::post('/orders/{order}/update', [App\Http\Controllers\PegawaiDashboardController::class, 'updateOrderStatus'])->name('pegawai.orders.update');

    // Delivery Management (CRUD)
    Route::get('/deliveries', [\App\Http\Controllers\PegawaiDeliveryController::class, 'index'])->name('pegawai.deliveries.index');
    Route::get('/deliveries/create', [\App\Http\Controllers\PegawaiDeliveryController::class, 'create'])->name('pegawai.deliveries.create');
    Route::post('/deliveries', [\App\Http\Controllers\PegawaiDeliveryController::class, 'store'])->name('pegawai.deliveries.store');
    Route::get('/deliveries/{delivery}/detail', [\App\Http\Controllers\PegawaiDeliveryController::class, 'detail'])->name('pegawai.deliveries.detail');
    Route::post('/deliveries/{delivery}/update', [\App\Http\Controllers\PegawaiDeliveryController::class, 'update'])->name('pegawai.deliveries.update');
});

// =================== QR VERIFIKASI (PEGAWAI/ADMIN) ===================
Route::middleware(['auth', 'role:admin,employee'])->group(function () {
    Route::get('/qr/verify', [\App\Http\Controllers\QrVerificationController::class, 'showForm'])->name('qr.verify.form');
    Route::post('/qr/verify', [\App\Http\Controllers\QrVerificationController::class, 'verify'])->name('qr.verify');
});

// =================== ABOUT & CONTACT ===================
Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');

// =================== PROFILE & ADDRESS ===================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserProfileController::class, 'show'])->name('user.profile');
    Route::put('/profile', [UserProfileController::class, 'update'])->name('user.profile.update');
    Route::get('/profile/address/create', [UserAddressController::class, 'create'])->name('user.address.create');
    Route::post('/profile/address', [UserAddressController::class, 'store'])->name('user.address.store');
    Route::delete('/profile/address/{address}', [UserAddressController::class, 'delete'])->name('user.address.delete');
});
