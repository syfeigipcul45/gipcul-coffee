<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BahanPokok extends Model
{
    protected $table = 'bahan_pokoks';

    protected $fillable = [
        'nama_bahan',
        'jumlah_berat',
        'satuan_id',
        'harga',
    ];

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }
}
