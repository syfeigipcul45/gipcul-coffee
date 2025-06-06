<?php

namespace App\Filament\Resources\ResepResource\Pages;

use App\Filament\Resources\ResepResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReseps extends ListRecords
{
    protected static string $resource = ResepResource::class;
    protected static ?string $title = 'Daftar Resep Minuman';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
