<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchooljaarResource\Pages;
use App\Filament\Resources\SchooljaarResource\RelationManagers;
use App\Models\Schooljaar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SchooljaarResource extends Resource
{
    protected static ?string $model = Schooljaar::class;
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
                    ->autofocus()
                    ->required()
                    ->label('Einddatum')
                    ->hint('laatste dag van het schooljaar'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
