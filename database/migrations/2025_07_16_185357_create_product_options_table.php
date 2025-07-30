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
        Schema::create('product_options', function (Blueprint $table) {
            $table->id(); // Kolom ID utama

            // Foreign Key ke tabel 'products'
            // Ini menghubungkan opsi dengan produk tertentu (misal: "Ukuran Large" untuk "Pepperoni Pizza")
            $table->foreignId('product_id')
                  ->constrained('products') // Merujuk ke tabel 'products'
                  ->onDelete('cascade'); // Jika produk dihapus, opsi-nya juga dihapus

            $table->string('type'); // Jenis opsi (misal: "Size", "Crust Type", "Spicy Level")
            $table->string('name'); // Nama opsi (misal: "Regular", "Medium", "Large" untuk type "Size")
            $table->decimal('price_modifier', 10, 2)->default(0.00); // Penyesuaian harga (+/- dari harga dasar produk)
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_options');
    }
};