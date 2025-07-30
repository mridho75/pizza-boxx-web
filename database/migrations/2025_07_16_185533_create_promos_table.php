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
        Schema::create('promos', function (Blueprint $table) {
            $table->id(); // Kolom ID utama
            $table->string('code')->unique()->nullable(); // Kode kupon (misal: "DISKON10", boleh kosong jika promo otomatis)
            $table->string('name'); // Nama promosi (misal: "Diskon 10% Semua Pizza", "Buy 1 Get 1 Free")
            $table->text('description')->nullable(); // Deskripsi detail promo
            $table->enum('type', ['percentage', 'fixed_amount', 'buy_x_get_y', 'free_delivery']); // Tipe promo
            $table->decimal('value', 10, 2)->nullable(); // Nilai promo (misal: 10 untuk %, 20000 untuk fixed_amount)
            $table->decimal('min_order_amount', 10, 2)->nullable(); // Minimum belanja untuk bisa pakai promo
            $table->timestamp('start_date'); // Tanggal mulai berlaku promo
            $table->timestamp('end_date')->nullable(); // Tanggal berakhir promo (boleh kosong jika tidak ada batas waktu)
            $table->integer('usage_limit')->nullable(); // Batas penggunaan total (misal: 100 kali)
            $table->integer('uses')->default(0); // Jumlah kali promo sudah digunakan
            $table->boolean('is_active')->default(true); // Status promo aktif/tidak
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};