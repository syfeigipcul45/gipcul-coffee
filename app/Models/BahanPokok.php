<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BahanPokok extends Model
{
    protected $table = 'bahan_pokoks';

    protected $fillable = [
        'nama_bahan',
    ];
}
