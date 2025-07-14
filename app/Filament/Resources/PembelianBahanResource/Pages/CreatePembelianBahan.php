<?php

namespace App\Filament\Resources\PembelianBahanResource\Pages;

use App\Filament\Resources\PembelianBahanResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePembelianBahan extends CreateRecord
{
    protected static string $resource = PembelianBahanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Pembelian bahan dibuat')
            ->body('Pembelian bahan baru telah berhasil dibuat.');
    }
}
