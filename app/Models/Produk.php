<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Produk extends Model implements HasMedia
{
    protected $table = 'produks';
    use \Spatie\MediaLibrary\InteractsWithMedia;

    protected $fillable = [
        'nama_produk',
        'harga',
        'kategori',
        'deskripsi',
    ];

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('thumbnail')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }
}
