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
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom location_id setelah kolom 'role'
            $table->foreignId('location_id')
                  ->nullable() // Boleh kosong, karena pelanggan tidak punya location_id
                  ->constrained('locations') // Merujuk ke tabel 'locations'
                  ->onDelete('set null') // Jika lokasi dihapus, location_id di user jadi NULL
                  ->after('role'); // Tambahkan setelah kolom 'role'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus foreign key constraint terlebih dahulu
            $table->dropConstrainedForeignId('location_id');
            // Hapus kolom location_id
            $table->dropColumn('location_id');
        });
    }
};