<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\SemesterResource\Pages\ListSemesters;
use App\Filament\Resources\SemesterResource\Pages\CreateSemester;
use App\Filament\Resources\SemesterResource\Pages\EditSemester;
use App\Enums\Volgorde;
use App\Filament\Resources\SemesterResource\Pages;
use App\Models\Semester;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SemesterResource extends Resource
{
    protected static ?string $model = Semester::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Basisdata';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('schooljaar_id')
                    ->relationship('schooljaar')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "Schooljaar {$record->start->format('y')}-{$record->eind->format('y')}")
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
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
                    ->required(),
                Select::make('volgorde')
                    ->options(Volgorde::class)
                    ->required(),
                DatePicker::make('start')
                    ->hint('eerste dag van semester')
                    ->required(),
                DatePicker::make('eind')
                    ->hint('laatste dag van semester')
                    ->required(),
                TextInput::make('cohort')
                    ->numeric()
                    ->hint('optioneel')
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
                TextColumn::make('naam')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('schooljaar.naam')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('volgorde'),
                TextColumn::make('start')
                    ->date()
                    ->sortable(),
                TextColumn::make('eind')
                    ->date(),
                TextColumn::make('cohort')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('start', 'desc');
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
            'index' => ListSemesters::route('/'),
            'create' => CreateSemester::route('/create'),
            'edit' => EditSemester::route('/{record}/edit'),
        ];
    }
}
