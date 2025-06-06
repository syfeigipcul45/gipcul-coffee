<?php

namespace App\Filament\Resources\BahanPokokResource\Pages;

use App\Filament\Resources\BahanPokokResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBahanPokoks extends ListRecords
{
    protected static string $resource = BahanPokokResource::class;
    protected static ?string $title = 'Daftar Bahan Pokok';

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
