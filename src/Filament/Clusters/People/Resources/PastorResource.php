<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources;

use Bishopm\Church\Filament\Clusters\People;
use Bishopm\Church\Filament\Clusters\People\Resources\PastorResource\Pages;
use Bishopm\Church\Filament\Clusters\People\Resources\PastorResource\RelationManagers;
use Bishopm\Church\Models\Individual;
use Bishopm\Church\Models\Pastor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PastorResource extends Resource
{
    protected static ?string $model = Pastor::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = People::class;

    protected static ?string $modelLabel = 'Pastoral carer';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('individual_id')
                    ->relationship('individual', 'id')
                    ->getOptionLabelFromRecordUsing(fn (Individual $record) => "{$record->firstname} {$record->surname}")
                    ->required(),
                Forms\Components\Toggle::make('active'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('individual.surname')->label('Surname')
                    ->sortable(),
                Tables\Columns\TextColumn::make('individual.firstname')->label('First name')
                    ->sortable(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean(),
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
            RelationManagers\PastoralcasesRelationManager::class,
            RelationManagers\PastoralnotesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPastors::route('/'),
            'create' => Pages\CreatePastor::route('/create'),
            'edit' => Pages\EditPastor::route('/{record}/edit'),
        ];
    }
}
