<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Dua perubahan untuk dashboard admin:
     * 1. Role "admin" ditambahkan ke enum users.role (admin dibuat via seeder,
     *    tidak bisa self-register).
     * 2. Tabel cuisine_types: daftar resmi jenis makanan yang dikelola admin.
     *    merchant_profiles.cuisine_type TETAP string biasa (denormalisasi
     *    sengaja): profil menyimpan label saat dipilih, sehingga perubahan
     *    nama jenis di kemudian hari tidak menimpa data yang sudah ada —
     *    prinsip yang sama dengan price_snapshot di order_items.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['merchant', 'customer', 'admin'])->change();
        });

        Schema::create('cuisine_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuisine_types');

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['merchant', 'customer'])->change();
        });
    }
};
