<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchooljaarResource\Pages;
use App\Models\Schooljaar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SchooljaarResource extends Resource
{
    protected static ?string $model = Schooljaar::class;

    protected static ?string $navigationGroup = 'Basisdata';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $pluralModelLabel = 'schooljaren';

    protected static ?string $slug = 'schooljaren';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('start')
                    ->autofocus()
                    ->required()
                    ->label('Startdatum')
                    ->hint('eerste dag van het schooljaar'),
                Forms\Components\DatePicker::make('eind')
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
                Tables\Columns\TextColumn::make('naam'),
                Tables\Columns\TextColumn::make('start')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('eind')
                    ->date()
                    ->sortable(),
            ])
            ->defaultSort('start', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSchooljaars::route('/'),
            'create' => Pages\CreateSchooljaar::route('/create'),
            'edit' => Pages\EditSchooljaar::route('/{record}/edit'),
        ];
    }
}
