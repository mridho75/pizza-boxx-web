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
        Schema::table('orders', function (Blueprint $table) {
            // Tambahkan kolom is_offline setelah payment_method
            $table->boolean('is_offline')->default(false)->after('payment_method');
            // Tambahkan kolom untuk QR code dan status verifikasi setelah delivery_notes
            $table->string('qr_code_path')->nullable()->after('delivery_notes');
            $table->boolean('is_qr_verified')->default(false)->after('qr_code_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Hapus kolom jika migrasi di-rollback
            $table->dropColumn(['is_offline', 'qr_code_path', 'is_qr_verified']);
        });
    }
};