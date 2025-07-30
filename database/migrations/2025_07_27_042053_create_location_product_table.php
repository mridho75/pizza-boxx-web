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
        Schema::create('location_product', function (Blueprint $table) {
            $table->id(); // Kolom ID utama
            $table->foreignId('location_id')->constrained()->onDelete('cascade'); // Foreign key ke tabel locations
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Foreign key ke tabel products
            $table->boolean('is_available')->default(true); // Status ketersediaan produk di lokasi ini
            $table->timestamps(); // Kolom created_at dan updated_at

            // Tambahkan unique constraint agar satu produk hanya bisa memiliki satu entri per lokasi
            $table->unique(['location_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_product');
    }
};