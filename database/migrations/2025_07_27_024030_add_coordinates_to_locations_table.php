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
        Schema::table('locations', function (Blueprint $table) {
            // Tambahkan kolom latitude, longitude, dan delivery_radius_km
            $table->decimal('latitude', 10, 8)->nullable()->after('delivery_area_geojson');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->integer('delivery_radius_km')->default(5)->after('longitude'); // Radius dalam KM, default 5 KM
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            // Hapus kolom jika migrasi di-rollback
            $table->dropColumn(['latitude', 'longitude', 'delivery_radius_km']);
        });
    }
};