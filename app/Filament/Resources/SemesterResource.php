<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SemesterResource\Pages;
use App\Filament\Resources\SemesterResource\RelationManagers;
use App\Models\Semester;
use App\Enums\Volgorde;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SemesterResource extends Resource
{
    protected static ?string $model = Semester::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('schooljaar_id')
                    ->relationship('schooljaar')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "Schooljaar {$record->start->format('y')}-{$record->eind->format('y')}")
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
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
                    ->required(),
                Forms\Components\Select::make('volgorde')
                    ->options(Volgorde::class)
                    ->required(),
                Forms\Components\DatePicker::make('start')
                    ->hint('eerste dag van semester')
                    ->required(),
                Forms\Components\DatePicker::make('eind')
                    ->hint('laatste dag van semester')
                    ->required(),
                Forms\Components\TextInput::make('cohort')
                    ->numeric()
                    ->hint("optioneel")
                    ->helperText("Geldt dit semester alleen voor een specifiek cohort? Vul hier dan de tweecijferige afkorting in, bijvoorbeeld '21'.")
                    ->columnSpanFull()
                    ->minValue(10)
                    ->maxValue(99),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('naam')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('schooljaar.naam')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('volgorde'),
                Tables\Columns\TextColumn::make('start')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('eind')
                    ->date(),
                Tables\Columns\TextColumn::make('cohort')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListSemesters::route('/'),
            'create' => Pages\CreateSemester::route('/create'),
            'edit' => Pages\EditSemester::route('/{record}/edit'),
        ];
    }
}
