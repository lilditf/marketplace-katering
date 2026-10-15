<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Profil perusahaan katering (1:1 dengan User role=merchant).
 * Memuat data katalog: kota & jenis makanan (keduanya ber-index dan dipakai
 * filter pencarian via scopeSearch), plus status aktif untuk menyembunyikan
 * katering dari hasil pencarian tanpa menghapus akun.
 */
class MerchantProfile extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'city',
        'address',
        'cuisine_type',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Scope pencarian katering untuk portal customer:
     * keyword dicocokkan ke nama perusahaan/deskripsi/kota, plus filter
     * kota dan jenis makanan. Hanya katering aktif yang ditampilkan.
     */
    public function scopeSearch(Builder $query, ?string $keyword = null, ?string $city = null, ?string $cuisine = null): Builder
    {
        return $query->where('is_active', true)
            ->when($keyword, function (Builder $q) use ($keyword) {
                $q->where(function (Builder $q2) use ($keyword) {
                    $q2->where('company_name', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%")
                        ->orWhere('city', 'like', "%{$keyword}%");
                });
            })
            ->when($city, fn (Builder $q) => $q->where('city', $city))
            ->when($cuisine, fn (Builder $q) => $q->where('cuisine_type', $cuisine));
    }
}
