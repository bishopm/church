<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources\PastorResource\RelationManagers;

use Bishopm\Church\Models\Household;
use Bishopm\Church\Models\Individual;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Bishopm\Church\Models\Pastoralcase;
use Filament\Forms\Components\MorphToSelect;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PastoralcasesRelationManager extends RelationManager
{
    protected static string $relationship = 'Pastoralcases';

    protected static ?string $title = 'Pastoral cases';

    protected static ?string $modelLabel = 'pastoral case';

    public $record;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('details')
                    ->required()
                    ->maxLength(255),
                Forms\Components\MorphToSelect::make('pastorable')->label('Individual or Household')
                    ->types([
                        Forms\Components\MorphToSelect\Type::make(Individual::class)
                            ->titleAttribute('fullname'),
                        Forms\Components\MorphToSelect\Type::make(Household::class)
                            ->titleAttribute('addressee'),
                        ])
                    ->searchable()
                    ->model(Pastoralcase::class),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('details')
            ->columns([     
                Tables\Columns\TextColumn::make('pastorable')->label('Individual / Household')
                    ->formatStateUsing(function ($state){
                        if (isset($state['fullname'])){
                            return $state['fullname'];
                        } else {
                            return $state['addressee'];
                        }
                    }),
                Tables\Columns\TextColumn::make('details')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->recordUrl(fn (Pastoralcase $record): string => route('filament.admin.people.resources.pastoralcases.edit', $record->id))
            ->actions([
                Action::make('Manage')->url(fn (Pastoralcase $record): string => route('filament.admin.people.resources.pastoralcases.edit', $record->id)),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
