<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Baris item sebuah order. price_snapshot menyalin harga menu SAAT transaksi
 * terjadi, sehingga perubahan harga menu kemudian tidak mengubah invoice
 * lama (integritas historis).
 */
class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'menu_id',
        'qty',
        'price_snapshot',
        'subtotal',
    ];

    protected $casts = [
        'qty' => 'integer',
        'price_snapshot' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }
}
