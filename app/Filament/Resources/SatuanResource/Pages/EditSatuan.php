<?php

namespace App\Filament\Resources\SatuanResource\Pages;

use App\Filament\Resources\SatuanResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditSatuan extends EditRecord
{
    protected static string $resource = SatuanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title('Satuan berhasil diperbarui')
            ->body('Perubahan pada satuan telah berhasil disimpan.');
    }
}
