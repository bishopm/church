<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources\PastorResource\RelationManagers;

use Bishopm\Church\Models\Individual;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class IndividualsRelationManager extends RelationManager
{
    protected static string $relationship = 'individuals';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('individual_id')
                    ->label('Pastoral case - individual')
                    ->required()
                    ->options(Individual::orderBy('surname')->get()->pluck('surname', 'id'))
                    ->searchable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
        ->recordTitle(fn (Individual $record): string => "{$record->firstname} {$record->surname}")
            ->columns([
                Tables\Columns\TextColumn::make('fullname')
                ->label('Name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()->recordSelectSearchColumns(['firstname', 'surname'])->label('Add pastoral case'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')->url(fn ($record): string => route('filament.admin.people.resources.individuals.edit', $record))->icon('heroicon-m-eye'),
                Tables\Actions\DetachAction::make()->label('Remove'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
