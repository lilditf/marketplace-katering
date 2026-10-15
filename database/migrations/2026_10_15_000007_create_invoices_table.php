<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            // unique() = relasi 1:1 dengan order; cascade jika order dihapus.
            $table->foreignId('order_id')->constrained()->cascadeOnDelete()->unique();
            $table->string('invoice_number', 30)->unique();
            $table->date('issued_at');
            $table->decimal('total', 12, 2);
            $table->enum('payment_status', ['belum_lunas', 'lunas'])->default('belum_lunas');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
