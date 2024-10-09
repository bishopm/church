<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources\RosterResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Hugomyb\FilamentMediaAction\Forms\Components\Actions\MediaAction;
use Illuminate\Database\Eloquent\Builder;

class RostergroupsRelationManager extends RelationManager
{
    protected static string $relationship = 'rostergroups';

    protected static ?string $title = 'Roster groups';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('group_id')
                ->relationship('group','groupname')
                ->required(),
                Forms\Components\TextInput::make('maxpeople')
                ->required()
                ->numeric(),
                Forms\Components\TextInput::make('video')
                ->maxLength(255)
                ->suffixAction(MediaAction::make('showVideo')
                    ->icon('heroicon-m-video-camera')
                    ->media(fn (Get $get) => $get('video'))
                ),
                Forms\Components\Select::make('extrainfo')
                ->label('Extra info with SMS?')
                ->options([
                    'reading' => 'Reading'
                ])
                ->placeholder(''),
                Forms\Components\ToggleButtons::make('editable')
                ->label('Editable?')
                ->boolean()
                ->inline(false)
                ->default(false)
                ->grouped()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('group.groupname')
            ->columns([
                Tables\Columns\TextColumn::make('group.groupname')->sortable(),
            ])
            ->defaultSort('group.groupname','ASC')
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Add group to roster'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
