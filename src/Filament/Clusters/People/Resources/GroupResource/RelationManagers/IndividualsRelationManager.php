<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources\GroupResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Bishopm\Church\Models\Individual;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IndividualsRelationManager extends RelationManager
{
    protected static string $relationship = 'individuals';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('individual_id')
                    ->label('Group member')
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
                Tables\Actions\AttachAction::make()->recordSelectSearchColumns(['firstname', 'surname'])->label('Add group member'),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}