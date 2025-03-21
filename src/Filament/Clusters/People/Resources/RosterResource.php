<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources;

use Bishopm\Church\Filament\Clusters\People;
use Bishopm\Church\Filament\Clusters\People\Resources\RosterResource\Pages;
use Bishopm\Church\Filament\Clusters\People\Resources\RosterResource\RelationManagers;
use Bishopm\Church\Models\Group;
use Bishopm\Church\Models\Roster;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RosterResource extends Resource
{
    protected static ?int $navigationSort = 4;

    protected static ?string $model = Roster::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $cluster = People::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('roster')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('dayofweek')
                    ->label('Day of week')
                    ->required()
                    ->options([
                        'Sunday' => 'Sunday',
                        'Monday' => 'Monday',
                        'Tuesday' => 'Tuesday',
                        'Wednesday' => 'Wednesday',
                        'Thursday' => 'Thursday',
                        'Friday' => 'Friday',
                        'Saturday' => 'Saturday'
                    ])
                    ->live()
                    ->default('Sunday'),
                Forms\Components\TextInput::make('message')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('includepreacher')->label('Show preacher on roster')
                    ->hidden(fn (Get $get): bool => !($get('dayofweek') == "Sunday")),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('roster')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dayofweek')
                    ->label('Day of week')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordUrl(fn (Roster $record): string => route('filament.admin.people.resources.rosters.manage', $record))
            ->actions([
                Action::make('Manage')->url(fn (Roster $record): string => route('filament.admin.people.resources.rosters.manage', $record)),
                Tables\Actions\EditAction::make()
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
            RelationManagers\RostergroupsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRosters::route('/'),
            'create' => Pages\CreateRoster::route('/create'),
            'manage' => Pages\ManageRoster::route('/{record}'),
            'edit' => Pages\EditRoster::route('/{record}/edit'),
        ];
    }
}
