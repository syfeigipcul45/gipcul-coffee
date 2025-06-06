<?php

namespace App\Filament\Resources\StokBahanResource\Pages;

use App\Filament\Resources\StokBahanResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditStokBahan extends EditRecord
{
    protected static string $resource = StokBahanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title('Stok Bahan Berhasil Diperbarui')
            ->body('Stok bahan telah berhasil diperbarui.');
    }
}
