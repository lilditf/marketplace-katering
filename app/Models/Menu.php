<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Menu katering: nama, deskripsi, harga, foto, kategori, dan flag
 * is_available (cara menyembunyikan menu tanpa menghapus — menu yang sudah
 * pernah dipesan dilindungi FK RESTRICT demi integritas invoice).
 * scopeAvailable() dipakai katalog customer agar menu nonaktif tak tampil.
 */
class Menu extends Model
{
    /**
     * Tabel eksplisit: pluralisasi default "Menu" bisa ambigu,
     * jadi nama tabel ditegaskan di sini.
     */
    protected $table = 'menus';

    public const CATEGORIES = ['Makanan Berat', 'Makanan Ringan', 'Minuman', 'Paket'];

    protected $fillable = [
        'merchant_profile_id',
        'name',
        'description',
        'price',
        'photo_path',
        'category',
        'is_available',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    public function merchantProfile(): BelongsTo
    {
        return $this->belongsTo(MerchantProfile::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_available', true);
    }
}
