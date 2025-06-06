<?php

namespace App\Filament\Resources\SatuanResource\Pages;

use App\Filament\Resources\SatuanResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateSatuan extends CreateRecord
{
    protected static string $resource = SatuanResource::class;


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title('Satuan berhasil dibuat')
            ->body('Satuan baru telah berhasil dibuat.');
    }
}
