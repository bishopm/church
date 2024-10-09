<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources;

use Bishopm\Church\Filament\Clusters\People\Resources\HouseholdResource\Pages;
use Bishopm\Church\Filament\Clusters\People\Resources\HouseholdResource\RelationManagers;
use Bishopm\Church\Filament\Clusters\People;
use Bishopm\Church\Models\Household;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HouseholdResource extends Resource
{
    protected static ?int $navigationSort = 3;
    
    protected static ?string $model = Household::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $cluster = People::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('addressee')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address1')
                    ->maxLength(255),
                Forms\Components\TextInput::make('address2')
                    ->maxLength(255),
                Forms\Components\TextInput::make('address3')
                    ->maxLength(255),
                Forms\Components\TextInput::make('homephone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('householdcell')
                    ->numeric(),
                Forms\Components\TextInput::make('sortsurname')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('addressee')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sortsurname')
                    ->searchable()
                    ->label('Surname'),
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
            RelationManagers\IndividualsRelationManager::class,
            RelationManagers\AnniversariesRelationManager::class,
            RelationManagers\PastoralnotesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHouseholds::route('/'),
            'create' => Pages\CreateHousehold::route('/create'),
            'edit' => Pages\EditHousehold::route('/{record}/edit'),
        ];
    }
}
