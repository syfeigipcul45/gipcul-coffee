<?php

namespace App\Filament\Resources\ResepResource\Pages;

use App\Filament\Resources\ResepResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateResep extends CreateRecord
{
    protected static string $resource = ResepResource::class;
    protected static ?string $title = 'Tambah Resep Minuman';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Resep Minuman berhasil dibuat')
            ->success()
            ->body('Resep Minuman baru telah berhasil ditambahkan ke dalam sistem.');
    }

}
