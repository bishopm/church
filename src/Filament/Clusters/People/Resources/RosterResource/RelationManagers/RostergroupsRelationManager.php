<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources\RosterResource\RelationManagers;

use Bishopm\Church\Models\Video;
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
                Forms\Components\TextInput::make('maxpeople')->label('Maximum number of people')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('videos')
                    ->multiple()
                    ->formatStateUsing(function ($state){
                        if ($state){
                            return json_decode($state);
                        }
                    })
                    ->options(function (){
                        return Video::orderBy('title')->get()->pluck('title','id');
                    }),
                Forms\Components\Select::make('extrainfo')
                    ->label('Extra info with SMS?')
                    ->options([
                        'reading' => 'Reading'
                    ])
                    ->placeholder('')
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('group.groupname')
            ->columns([
                Tables\Columns\TextColumn::make('group.groupname')->sortable(),
                Tables\Columns\TextColumn::make('videos')
                    ->formatStateUsing(function ($state){
                        return count(json_decode($state));
                    })

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
