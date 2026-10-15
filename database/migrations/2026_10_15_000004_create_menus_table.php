<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_profile_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->string('photo_path')->nullable();
            $table->enum('category', ['Makanan Berat', 'Makanan Ringan', 'Minuman', 'Paket'])
                ->default('Makanan Berat');
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            $table->index(['merchant_profile_id', 'is_available']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
