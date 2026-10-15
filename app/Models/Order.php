<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Order pembelian dari kantor ke katering.
 * order_number unik (ORD-tanggal-urut, diturunkan dari primary key di dalam
 * transaction agar anti race condition). Status mengalir satu arah:
 * pending → dikonfirmasi → dikirim → selesai (atau dibatalkan).
 */
class Order extends Model
{
    /**
     * Alur status: pending -> dikonfirmasi -> dikirim -> selesai.
     * "dibatalkan" bisa dari pending/dikonfirmasi (oleh merchant).
     */
    public const STATUSES = ['pending', 'dikonfirmasi', 'dikirim', 'selesai', 'dibatalkan'];

    public const STATUS_LABELS = [
        'pending' => 'Menunggu Konfirmasi',
        'dikonfirmasi' => 'Dikonfirmasi',
        'dikirim' => 'Dikirim',
        'selesai' => 'Selesai',
        'dibatalkan' => 'Dibatalkan',
    ];

    protected $fillable = [
        'order_number',
        'merchant_profile_id',
        'customer_profile_id',
        'delivery_date',
        'notes',
        'subtotal',
        'status',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'subtotal' => 'decimal:2',
    ];

    public function merchantProfile(): BelongsTo
    {
        return $this->belongsTo(MerchantProfile::class);
    }

    public function customerProfile(): BelongsTo
    {
        return $this->belongsTo(CustomerProfile::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }
}
