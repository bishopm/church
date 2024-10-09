<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources\IndividualResource\RelationManagers;

use Bishopm\Church\Models\Group;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class GroupsRelationManager extends RelationManager
{
    protected static string $relationship = 'groups';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('groupname')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('groupname')
            ->columns([
                Tables\Columns\TextColumn::make('groupname'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()->label('Add group'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->label('Edit')
                ->url(fn (Group $record): string => route('filament.admin.people.resources.groups.edit', ['record' => $record->id])),
                Tables\Actions\DetachAction::make()->label('Remove'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
