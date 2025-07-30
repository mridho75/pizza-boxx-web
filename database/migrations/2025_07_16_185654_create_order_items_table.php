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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id(); // Kolom ID utama

            // Foreign Key ke tabel 'orders'
            // Menghubungkan item ini ke pesanan induknya
            $table->foreignId('order_id')
                  ->constrained('orders') // Merujuk ke tabel 'orders'
                  ->onDelete('cascade'); // Jika pesanan dihapus, itemnya juga dihapus

            // Foreign Key ke tabel 'products'
            // Menghubungkan item ini ke produk yang sebenarnya
            $table->foreignId('product_id')
                  ->constrained('products') // Merujuk ke tabel 'products'
                  ->onDelete('cascade'); // Jika produk dihapus, item yang terkait juga dihapus

            $table->string('product_name'); // Nama produk saat dipesan (untuk riwayat, jika nama produk asli berubah)
            $table->integer('quantity'); // Jumlah produk yang dipesan
            $table->decimal('unit_price', 10, 2); // Harga satuan produk saat dipesan

            // Opsional: Detail kustomisasi produk (opsi seperti ukuran, adonan)
            $table->json('options')->nullable(); // Simpan sebagai JSON (misal: {"size": "Large", "crust": "Thin"})

            // Opsional: Detail add-ons yang dipilih (misal: extra cheese, saus)
            $table->json('addons')->nullable(); // Simpan sebagai JSON (misal: [{"id": 1, "name": "Extra Cheese", "price": 10000}])

            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};