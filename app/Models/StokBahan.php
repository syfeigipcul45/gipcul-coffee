<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokBahan extends Model
{
    protected $table = 'stok_bahans';

    protected $fillable = [
        'bahan_id',
        'jumlah_berat',
        'satuan_id',
    ];

    public function bahanPokok()
    {
        return $this->belongsTo(BahanPokok::class, 'bahan_id');
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }
}
