<?php

namespace Bishopm\Church\Filament\Clusters\Property\Resources;

use Bishopm\Church\Filament\Clusters\Property;
use Bishopm\Church\Filament\Clusters\Property\Resources\MaintenancetaskResource\Pages;
use Bishopm\Church\Filament\Clusters\Property\Resources\MaintenancetaskResource\RelationManagers;
use Bishopm\Church\Models\Individual;
use Bishopm\Church\Models\Maintenancetask;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MaintenancetaskResource extends Resource
{
    protected static ?int $navigationSort = 4;

    protected static ?string $model = Maintenancetask::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench';

    protected static ?string $cluster = Property::class;

    protected static ?string $modelLabel = 'Maintenance task';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('details')
                    ->required()
                    ->maxLength(199),
                Forms\Components\Select::make('individual_id')->label('Assigned to')
                    ->relationship('individual', 'id')
                    ->searchable(['firstname', 'surname'])
                    ->getOptionLabelFromRecordUsing(fn (Individual $record) => "{$record->firstname} {$record->surname}"),
                Forms\Components\DatePicker::make('completed_at'),
                Forms\Components\Select::make('venue_id')->label('Venue')
                    ->relationship('venue', 'venue'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('details')
                    ->searchable(),
                Tables\Columns\TextColumn::make('individual.fullname')
                    ->label('Assigned to')
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed_at')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('venue.venue')
                    ->label('Venue')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('is_open')
                    ->query(fn (Builder $query) => $query->whereNull('completed_at'))
                    ->default(true),
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
            'index' => Pages\ListMaintenancetasks::route('/'),
            'create' => Pages\CreateMaintenancetask::route('/create'),
            'edit' => Pages\EditMaintenancetask::route('/{record}/edit'),
        ];
    }
}
