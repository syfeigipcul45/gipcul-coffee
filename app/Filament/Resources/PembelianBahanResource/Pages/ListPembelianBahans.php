<?php

namespace App\Filament\Resources\PembelianBahanResource\Pages;

use App\Filament\Resources\PembelianBahanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPembelianBahans extends ListRecords
{
    protected static string $resource = PembelianBahanResource::class;
    protected static ?string $title = 'Daftar Pembelian Bahan';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
