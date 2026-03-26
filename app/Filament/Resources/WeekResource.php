<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\WeekResource\Pages\ListWeeks;
use App\Filament\Resources\WeekResource\Pages\EditWeek;
use App\Filament\Resources\WeekResource\Pages;
use App\Models\Week;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WeekResource extends Resource
{
    protected static ?string $model = Week::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Basisdata';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('semester_id')
                    ->relationship('semester')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "Semester {$record->naam}")
                    ->searchable()
                    ->preload()
                    ->disabled(),

                DatePicker::make('maandag')
                    ->required(),

                TextInput::make('nummer')
                    ->numeric()
                    ->required(),

                Select::make('type')
                    ->options([
                        'lesweek' => 'Lesweek',
                        'bufferweek' => 'Bufferweek',
                        'vakantie' => 'Vakantie',
                    ])
                    ->required(),

                TextInput::make('naam'),

                TextInput::make('cohort')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('semester.naam'),
                TextColumn::make('nummer')
                    ->label('Weeknummer')
                    ->numeric(),
                TextColumn::make('maandag')
                    ->date(),
                TextColumn::make('type'),
                TextColumn::make('naam'),
                TextColumn::make('cohort')
                    ->numeric(),
            ])
            ->filters([
                SelectFilter::make('semester_id')
                    ->relationship('semester', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "Semester {$record->naam}")
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('maandag', 'desc');
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
            'index' => ListWeeks::route('/'),
            // 'create' => Pages\CreateWeek::route('/create'), // Use the wizard instead
            'edit' => EditWeek::route('/{record}/edit'),
        ];
    }
}
