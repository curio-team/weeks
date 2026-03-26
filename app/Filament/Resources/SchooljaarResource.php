<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use App\Filament\Resources\SchooljaarResource\Pages\ListSchooljaars;
use App\Filament\Resources\SchooljaarResource\Pages\CreateSchooljaar;
use App\Filament\Resources\SchooljaarResource\Pages\EditSchooljaar;
use App\Filament\Resources\SchooljaarResource\Pages;
use App\Models\Schooljaar;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SchooljaarResource extends Resource
{
    protected static ?string $model = Schooljaar::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Basisdata';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $pluralModelLabel = 'schooljaren';

    protected static ?string $slug = 'schooljaren';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('start')
                    ->autofocus()
                    ->required()
                    ->label('Startdatum')
                    ->hint('eerste dag van het schooljaar'),
                DatePicker::make('eind')
                    ->required()
                    ->label('Einddatum')
                    ->hint('laatste dag van het schooljaar'),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('naam'),
                TextColumn::make('start')
                    ->date()
                    ->sortable(),
                TextColumn::make('eind')
                    ->date()
                    ->sortable(),
            ])
            ->defaultSort('start', 'desc')
            ->recordActions([
                EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSchooljaars::route('/'),
            'create' => CreateSchooljaar::route('/create'),
            'edit' => EditSchooljaar::route('/{record}/edit'),
        ];
    }
}
