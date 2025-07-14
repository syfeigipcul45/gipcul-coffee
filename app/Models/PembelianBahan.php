<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PembelianBahan extends Model
{
    protected $table = 'pembelian_bahans';

    protected $fillable = [
        'tanggal_beli',
        'total_harga',
    ];

    public function detailPembelians(): HasMany
    {
        // If your foreign key is 'pembelian_id' in detail_pembelians,
        // no arguments are needed here, as it's the default.
        return $this->hasMany(DetailPembelian::class, 'pembelian_id');
    }

    public function detailPembeliansCount(): int
    {
        return $this->detailPembelians()->count();
    }
}
