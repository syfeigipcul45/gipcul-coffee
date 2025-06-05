<?php

namespace App\Filament\Resources\BahanPokokResource\Pages;

use App\Filament\Resources\BahanPokokResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBahanPokok extends CreateRecord
{
    protected static string $resource = BahanPokokResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?\Filament\Notifications\Notification
    {
        return \Filament\Notifications\Notification::make()
            ->success()
            ->title('Bahan pokok dibuat')
            ->body('Bahan pokok baru telah berhasil dibuat.');
    }
}
