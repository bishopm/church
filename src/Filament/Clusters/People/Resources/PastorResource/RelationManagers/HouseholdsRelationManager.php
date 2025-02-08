<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources\PastorResource\RelationManagers;

use Bishopm\Church\Models\Household;
use Bishopm\Church\Models\Pastor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class HouseholdsRelationManager extends RelationManager
{
    protected static string $relationship = 'households';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Placeholder::make('pastorable_id')->label('Household')
                    ->content(function ($record){
                        $house=Household::find($record->pivot->pastorable_id);
                        return $house->addressee;
                    }),
                Forms\Components\TextInput::make('details')
                    ->label('Details'),
                Forms\Components\Toggle::make('active')
                    ->label('Active'),
                Forms\Components\Toggle::make('prayerlist')
                    ->label('Prayer list'),
                Forms\Components\TextInput::make('prayernote')
                    ->label('Prayer note'),
                Forms\Components\Placeholder::make('pastor_id')->label('Pastor')
                    ->content(function ($record){
                        $pastor=Pastor::with('individual')->where('id',$record->pivot->pastor_id)->first();
                        return $pastor->individual->fullname;
                    })
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitle(fn (Household $record): string => "{$record->addressee} pastoral note")
            ->columns([
                Tables\Columns\TextColumn::make('addressee')
                ->label('Name'),
                Tables\Columns\TextColumn::make('details')
                ->label('Details'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()->recordSelectSearchColumns(['addressee'])->label('Add pastoral case'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')->url(fn ($record): string => route('filament.admin.people.resources.households.edit', $record))->icon('heroicon-m-eye'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make()->label('Remove'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
