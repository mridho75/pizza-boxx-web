<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // Kolom ID utama

            // Foreign Key ke tabel 'users' (pelanggan yang membuat pesanan)
            // Bisa nullable karena ada kemungkinan 'guest checkout' (pelanggan tidak login)
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users') // Merujuk ke tabel 'users'
                  ->onDelete('set null'); // Jika user dihapus, user_id di sini jadi NULL

            // Foreign Key ke tabel 'locations' (toko yang memproses pesanan)
            $table->foreignId('location_id')
                  ->constrained('locations') // Merujuk ke tabel 'locations'
                  ->onDelete('cascade'); // Jika lokasi toko dihapus, pesanannya juga dihapus (sesuaikan jika perlu)

            $table->string('customer_name'); // Nama pelanggan (penting untuk guest checkout atau konfirmasi)
            $table->string('customer_email')->nullable(); // Email pelanggan (boleh kosong)
            $table->string('customer_phone'); // Nomor telepon pelanggan (wajib)

            $table->enum('order_type', ['delivery', 'pickup']); // Tipe pesanan (delivery atau pickup)
            $table->enum('payment_method', ['online', 'cash_on_delivery', 'card_on_pickup']); // Metode pembayaran
            $table->enum('status', [
                'pending',        // Menunggu konfirmasi toko / pembayaran
                'accepted',       // Pesanan diterima toko
                'preparing',      // Sedang disiapkan
                'ready_for_delivery', // Siap diantar / diambil
                'on_delivery',    // Sedang dalam pengantaran
                'delivered',      // Sudah diantar / diambil (final untuk delivery)
                'completed',      // Selesai (final untuk pickup / makan di tempat)
                'cancelled',      // Dibatalkan
                'refunded'        // Pembayaran dikembalikan
            ])->default('pending'); // Status awal pesanan

            $table->text('delivery_address')->nullable(); // Alamat pengiriman lengkap (untuk delivery)
            $table->text('delivery_notes')->nullable(); // Catatan pengiriman dari pelanggan
            $table->decimal('subtotal_amount', 10, 2); // Subtotal harga produk sebelum diskon/ongkir
            $table->decimal('discount_amount', 10, 2)->default(0.00); // Jumlah diskon
            $table->decimal('delivery_fee', 10, 2)->default(0.00); // Biaya pengiriman
            $table->decimal('total_amount', 10, 2); // Total harga akhir setelah diskon/ongkir

            // Opsional: Foreign Key ke promo yang digunakan
            $table->foreignId('promo_id')
                  ->nullable()
                  ->constrained('promos')
                  ->onDelete('set null');

            // Opsional: ID pegawai yang mengantar/menangani pesanan
            $table->foreignId('delivery_employee_id')
                  ->nullable()
                  ->constrained('users') // Merujuk ke tabel 'users' (dengan asumsi pegawai adalah user)
                  ->onDelete('set null');

            $table->timestamps(); // Kolom created_at dan updated_at
            $table->timestamp('delivered_at')->nullable(); // Waktu pesanan diantar/selesai
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};