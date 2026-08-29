<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_produk',
        'harga',
        'stok',
        'kategori',
        'deskripsi',
        'foto',
    ];

    protected $casts = [
        'harga' => 'integer',
        'stok'  => 'integer',
    ];

    public function getFotoUrlAttribute()
    {
        if (!$this->foto) return null;
        if (str_starts_with($this->foto, 'http')) return $this->foto;
        return '/storage/' . $this->foto;
    }
}