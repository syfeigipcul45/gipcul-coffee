<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    protected $fillable = [
        'produk_id',
        'deskripsi',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function detailReseps()
    {
        return $this->hasMany(DetailResep::class);
    }
}
