<?php

namespace App\Filament\Resources\SchooljaarResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\SchooljaarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSchooljaar extends EditRecord
{
    protected static string $resource = SchooljaarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
