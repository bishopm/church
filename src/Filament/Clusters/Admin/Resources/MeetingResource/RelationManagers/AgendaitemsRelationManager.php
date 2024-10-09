<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources\MeetingResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Component as Livewire;

class AgendaitemsRelationManager extends RelationManager
{
    protected static string $relationship = 'agendaitems';

    protected static ?string $modelLabel = 'agenda item';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('heading')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('sortorder')
                    ->required()
                    ->default(function (Livewire $livewire) {
                        return count(($livewire->ownerRecord->agendaitems));
                    })
                    ->numeric(),
                Forms\Components\TextInput::make('level')
                    ->required()
                    ->default(1)
                    ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('meetingdatetime')
            ->columns([
                Tables\Columns\TextColumn::make('heading'),
                Tables\Columns\TextColumn::make('sortorder')->label('Order'),
                Tables\Columns\TextColumn::make('level'),
            ])
            ->filters([
                //
            ])
            ->reorderable('sortorder')
            ->defaultSort('sortorder')
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
