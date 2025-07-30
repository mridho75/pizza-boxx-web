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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Kolom ID utama

            // Foreign Key ke tabel 'categories'
            // Ini menghubungkan produk dengan kategorinya (misal: Pizza Pepperoni masuk kategori 'Pizza')
            $table->foreignId('category_id')
                  ->constrained('categories') // Merujuk ke tabel 'categories'
                  ->onDelete('cascade'); // Jika kategori dihapus, produk di dalamnya juga dihapus

            $table->string('name'); // Nama produk (misal: "Pepperoni Pizza", "Spaghetti Bolognese")
            $table->text('description')->nullable(); // Deskripsi singkat produk (boleh kosong)
            $table->decimal('base_price', 10, 2); // Harga dasar produk (total 10 digit, 2 di belakang koma)
            $table->string('image_path')->nullable(); // Path/lokasi gambar produk (boleh kosong)
            $table->boolean('is_available')->default(true); // Status ketersediaan (defaultnya tersedia)
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};