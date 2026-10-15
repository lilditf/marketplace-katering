<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Profil kantor customer (1:1 dengan User role=customer):
 * nama kantor, kota/alamat pengiriman, dan PIC penanggung jawab pesanan.
 */
class CustomerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'office_name',
        'city',
        'address',
        'pic_name',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
