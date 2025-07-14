<?php

namespace App\Filament\Resources\PembelianBahanResource\Pages;

use App\Filament\Resources\PembelianBahanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPembelianBahan extends EditRecord
{
    protected static string $resource = PembelianBahanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getSavedNotification(): ?\Filament\Notifications\Notification
    {
        return \Filament\Notifications\Notification::make()
            ->success()
            ->title('Pembelian diperbarui')
            ->body('Pembelian bahan telah berhasil diperbarui.');
    }
    protected function getDeletedNotification(): ?\Filament\Notifications\Notification
    {
        return \Filament\Notifications\Notification::make()
            ->success()
            ->title('Pembelian dihapus')
            ->body('Pembelian bahan telah berhasil dihapus.');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
