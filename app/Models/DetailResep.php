<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailResep extends Model
{
    protected $fillable = [
        'resep_id',
        'bahan_id',
        'jumlah_berat',
        'satuan_id',
        'harga_bahan',
        'harga_pokok',
    ];

    public function resep()
    {
        return $this->belongsTo(Resep::class);
    }

    public function produk()
    {
        return $this->belongsTo(BahanPokok::class, 'bahan_id');
    }
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }
}
