<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

/**
 * Jenis makanan resmi yang dikelola admin (dashboard admin → Jenis Makanan).
 * Daftar aktifnya dipakai untuk: form registrasi merchant, edit profil
 * merchant, filter pencarian customer, dan validasi Rule::in di ketiganya.
 *
 * Daftar di-cache selamanya dan cache dibersihkan otomatis setiap ada
 * perubahan data (tambah/ubah/hapus) lewat event model di bawah.
 */
class CuisineType extends Model
{
    protected $fillable = ['name', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    /**
     * Nama-nama jenis yang aktif, di-cache agar tidak query berulang.
     * Cache dibersihkan otomatis saat data berubah (lihat booted()).
     *
     * @return array<int, string>
     */
    public static function activeNames(): array
    {
        return Cache::rememberForever('cuisine_types.active', fn () => static::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->pluck('name')
            ->all());
    }

    protected static function booted(): void
    {
        $clear = fn () => Cache::forget('cuisine_types.active');

        static::saved($clear);
        static::deleted($clear);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Merchant yang memakai jenis ini. Relasi via kolom string cuisine_type
     * (bukan FK id) — konsekuensi denormalisasi yang disengaja.
     */
    public function merchants(): HasMany
    {
        return $this->hasMany(MerchantProfile::class, 'cuisine_type', 'name');
    }
}
