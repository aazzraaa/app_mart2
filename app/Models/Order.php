<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'kode_order',
        'nama_pelanggan',
        'no_hp',
        'alamat',
        'total_harga',
        'metode_pembayaran',
        'status',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}