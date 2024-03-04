<?php

namespace App\Filament\Resources\SchooljaarResource\Pages;

use App\Filament\Resources\SchooljaarResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSchooljaar extends CreateRecord
{
    protected static string $resource = SchooljaarResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
