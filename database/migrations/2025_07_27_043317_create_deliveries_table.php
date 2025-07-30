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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->onDelete('cascade'); // Setiap order hanya punya satu delivery
            $table->foreignId('delivery_employee_id')->constrained('users')->onDelete('cascade'); // Kurir yang mengantar
            $table->enum('status', ['pending', 'on_delivery', 'delivered', 'failed'])->default('pending'); // Status pengantaran
            $table->timestamp('assigned_at')->nullable(); // Waktu ditugaskan
            $table->timestamp('picked_up_at')->nullable(); // Waktu diambil kurir
            $table->timestamp('delivered_at')->nullable(); // Waktu sampai ke pelanggan
            $table->text('notes')->nullable(); // Catatan kurir
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};