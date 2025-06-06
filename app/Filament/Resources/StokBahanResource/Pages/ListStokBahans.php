<?php

namespace App\Filament\Resources\StokBahanResource\Pages;

use App\Filament\Resources\StokBahanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStokBahans extends ListRecords
{
    protected static string $resource = StokBahanResource::class;
    protected static ?string $title = 'Daftar Stok Bahan';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
