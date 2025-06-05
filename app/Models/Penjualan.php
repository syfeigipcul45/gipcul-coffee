<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualans';

    protected $fillable = [
        'produk_id',
        'jumlah',
        'total_harga',
        'tanggal_penjualan',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
    public function scopeFilterByProduk($query, $produkId)
    {
        return $query->where('produk_id', $produkId);
    }
}
