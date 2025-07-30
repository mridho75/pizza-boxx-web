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
        Schema::create('locations', function (Blueprint $table) {
            $table->id(); // Kolom ID utama (auto-increment)
            $table->string('name'); // Nama lokasi/cabang (misal: "Pizza Boxx Depok")
            $table->string('address'); // Alamat lengkap lokasi
            $table->string('phone')->nullable(); // Nomor telepon lokasi (boleh kosong)
            $table->string('opening_hours')->nullable(); // Jam operasional (misal: "09:00 - 22:00")
            $table->text('delivery_area_geojson')->nullable(); // Opsional: Untuk area pengiriman kompleks, bisa pakai format GeoJSON
            $table->timestamps(); // Kolom created_at dan updated_at (otomatis diisi Laravel)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};