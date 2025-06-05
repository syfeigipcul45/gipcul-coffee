<?php

namespace App\Filament\Resources\BahanPokokResource\Pages;

use App\Filament\Resources\BahanPokokResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBahanPokok extends EditRecord
{
    protected static string $resource = BahanPokokResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?\Filament\Notifications\Notification
    {
        return \Filament\Notifications\Notification::make()
            ->success()
            ->title('Bahan Pokok diperbarui')
            ->body('Bahan Pokok telah berhasil diperbarui.');
    }
}
