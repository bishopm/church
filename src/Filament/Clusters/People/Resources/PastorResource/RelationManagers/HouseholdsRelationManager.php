<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources\PastorResource\RelationManagers;

use Bishopm\Church\Models\Household;
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
                Forms\Components\Select::make('addressee')
                    ->label('Pastoral case - household')
                    ->required()
                    ->options(Household::orderBy('addressee')->get()->pluck('addressee', 'id'))
                    ->searchable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitle(fn (Household $record): string => "{$record->addressee}")
            ->columns([
                Tables\Columns\TextColumn::make('addressee')
                ->label('Name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()->recordSelectSearchColumns(['addressee'])->label('Add pastoral case'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')->url(fn ($record): string => route('filament.admin.people.resources.households.edit', $record))->icon('heroicon-m-eye'),
                Tables\Actions\DetachAction::make()->label('Remove'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
