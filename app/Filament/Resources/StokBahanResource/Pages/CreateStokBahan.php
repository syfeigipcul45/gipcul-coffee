<?php

namespace App\Filament\Resources\StokBahanResource\Pages;

use App\Filament\Resources\StokBahanResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateStokBahan extends CreateRecord
{
    protected static string $resource = StokBahanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Stok Bahan Berhasil Dibuat')
            ->body('Stok bahan baru telah berhasil dibuat.');
    }
}
