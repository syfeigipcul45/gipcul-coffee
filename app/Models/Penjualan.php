<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualans';

    protected $fillable = [
        'nama_pembeli',
        'tanggal_beli',
        'total_harga',
        'jenis_pembayaran',
    ];

    public function detailPenjualans()
    {
        return $this->hasMany(DetailPenjualan::class);
    }

    // public function detailPenjualansCount()
    // {
    //     return $this->detailPenjualans()->sum('qty');
    // }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
    public function scopeFilterByProduk($query, $produkId)
    {
        return $query->where('produk_id', $produkId);
    }
}
