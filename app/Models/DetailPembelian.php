<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPembelian extends Model
{
    protected $table = 'detail_pembelians';

    protected $fillable = [
        'pembelian_id',
        'bahan_id',
        'jumlah_berat',
        'satuan_id',
        'harga',
    ];

    public function pembelianBahan()
    {
        return $this->belongsTo(PembelianBahan::class, 'pembelian_id');
    }

    public function bahanPokok()
    {
        return $this->belongsTo(BahanPokok::class, 'bahan_id');
    }
    
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }
}
