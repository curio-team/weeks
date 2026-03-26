<?php

namespace App\Filament\Resources\WeekResource\Pages;

use App\Filament\Resources\WeekResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListWeeks extends ListRecords
{
    protected static string $resource = WeekResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(), // Use the wizard
            Action::make('wizard')
                ->label('Maak nieuwe weken via de \'Nieuw schooljaar\' wizard')
                ->url(route('filament.admin.pages.wizard')),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
