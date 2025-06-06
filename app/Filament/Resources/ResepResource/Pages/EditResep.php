<?php

namespace App\Filament\Resources\ResepResource\Pages;

use App\Filament\Resources\ResepResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditResep extends EditRecord
{
    protected static string $resource = ResepResource::class;
    protected static ?string $title = 'Edit Resep Minuman';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Resep Minuman berhasil diperbarui')
            ->success()
            ->body('Perubahan pada resep minuman telah berhasil disimpan.');
    }
}
