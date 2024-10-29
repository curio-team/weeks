<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WeekResource\Pages;
use App\Filament\Resources\WeekResource\RelationManagers;
use App\Models\Week;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WeekResource extends Resource
{
    protected static ?string $model = Week::class;
    protected static ?string $navigationGroup = 'Basisdata';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    Forms\Components\Select::make('semester_id')
                        ->relationship('semester')
                        ->getOptionLabelFromRecordUsing(fn ($record) => "Semester {$record->naam}")
                        ->searchable()
                        ->preload()
                        ->disabled(),

                    Forms\Components\DatePicker::make('maandag')
                        ->required(),

                    Forms\Components\TextInput::make('nummer')
                        ->numeric()
                        ->required(),

                    Forms\Components\Select::make('type')
                        ->options([
                            'lesweek' => 'Lesweek',
                            'bufferweek' => 'Bufferweek',
                            'vakantie' => 'Vakantie',
                        ])
                        ->required(),

                    Forms\Components\TextInput::make('naam'),

                    Forms\Components\TextInput::make('cohort')
                        ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('semester.naam'),
                Tables\Columns\TextColumn::make('nummer')
                    ->numeric(),
                Tables\Columns\TextColumn::make('maandag')
                    ->date(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('naam'),
                Tables\Columns\TextColumn::make('cohort')
                    ->numeric(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('semester_id')
                    ->relationship('semester', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "Semester {$record->naam}")
                    ->preload(),
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
            'index' => Pages\ListWeeks::route('/'),
            // 'create' => Pages\CreateWeek::route('/create'), // Use the wizard instead
            'edit' => Pages\EditWeek::route('/{record}/edit'),
        ];
    }
}
