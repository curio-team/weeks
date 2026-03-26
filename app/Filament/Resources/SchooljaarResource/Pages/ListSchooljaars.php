<?php

namespace App\Filament\Resources\SchooljaarResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\SchooljaarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSchooljaars extends ListRecords
{
    protected static string $resource = SchooljaarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
