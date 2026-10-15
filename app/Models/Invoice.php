<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Invoice 1:1 dengan Order — dibuat otomatis dalam transaction yang sama
 * saat pembelian (fitur C4). payment_status: belum_lunas/lunas, ditandai
 * merchant. Bisa diakses kedua pihak (masing-masing hanya miliknya).
 */
class Invoice extends Model
{
    protected $fillable = [
        'order_id',
        'invoice_number',
        'issued_at',
        'total',
        'payment_status',
        'paid_at',
    ];

    protected $casts = [
        'issued_at' => 'date',
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'lunas';
    }
}
