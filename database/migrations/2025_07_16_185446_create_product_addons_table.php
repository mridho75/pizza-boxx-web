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
        Schema::create('product_addons', function (Blueprint $table) {
            $table->id(); // Kolom ID utama

            // Foreign Key ke tabel 'products'
            // Ini menghubungkan addon dengan produk tertentu (misal: "Ekstra Keju" untuk "Pepperoni Pizza")
            $table->foreignId('product_id')
                  ->constrained('products') // Merujuk ke tabel 'products'
                  ->onDelete('cascade'); // Jika produk dihapus, addon-nya juga dihapus

            $table->string('name'); // Nama addon (misal: "Ekstra Keju", "Saus Sambal")
            $table->decimal('price', 10, 2); // Harga addon (bukan modifier, ini harga penuh addon)
            $table->boolean('is_available')->default(true); // Status ketersediaan addon
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_addons');
    }
};