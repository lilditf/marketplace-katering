<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            // RESTRICT: menu yang sudah pernah dipesan tidak boleh dihapus,
            // agar riwayat invoice tetap utuh.
            $table->foreignId('menu_id')->constrained('menus')->restrictOnDelete();
            $table->unsignedInteger('qty');
            // Snapshot harga saat transaksi: perubahan harga menu di kemudian
            // hari tidak mengubah invoice yang sudah terbit.
            $table->decimal('price_snapshot', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
