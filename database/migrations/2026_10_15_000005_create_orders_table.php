<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 30)->unique();
            $table->foreignId('merchant_profile_id')->constrained()->restrictOnDelete();
            $table->foreignId('customer_profile_id')->constrained()->restrictOnDelete();
            $table->date('delivery_date');
            $table->text('notes')->nullable();
            $table->decimal('subtotal', 12, 2);
            $table->enum('status', ['pending', 'dikonfirmasi', 'dikirim', 'selesai', 'dibatalkan'])
                ->default('pending');
            $table->timestamps();

            $table->index(['merchant_profile_id', 'status']);
            $table->index('delivery_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
