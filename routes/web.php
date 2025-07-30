

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserDashboardController;

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
    Route::get('/dashboard', [App\Http\Controllers\PegawaiDashboardController::class, 'index'])->name('pegawai.dashboard');
    Route::get('/orders/{order}/detail', [App\Http\Controllers\PegawaiOrderDetailController::class, 'show'])->name('pegawai.orders.detail');
    Route::post('/orders/{order}/update', [App\Http\Controllers\PegawaiDashboardController::class, 'updateOrderStatus'])->name('pegawai.orders.update');
    Route::post('/deliveries/{delivery}/update', [App\Http\Controllers\PegawaiDashboardController::class, 'updateDeliveryStatus'])->name('pegawai.deliveries.update');
});

// =================== QR VERIFIKASI (PEGAWAI/ADMIN) ===================
Route::middleware(['auth', 'role:admin,employee'])->group(function () {
    Route::get('/qr/verify', [\App\Http\Controllers\QrVerificationController::class, 'showForm'])->name('qr.verify.form');
    Route::post('/qr/verify', [\App\Http\Controllers\QrVerificationController::class, 'verify'])->name('qr.verify');
});


