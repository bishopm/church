<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources\PastorResource\RelationManagers;

use Bishopm\Church\Models\Household;
use Bishopm\Church\Models\Individual;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Bishopm\Church\Models\Pastoralnote;
use Filament\Forms\Components\MorphToSelect;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PastoralnotesRelationManager extends RelationManager
{
    protected static string $relationship = 'pastoralnotes';

    protected static ?string $title = 'Pastoral notes';

    protected static ?string $modelLabel = 'pastoral note';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('details')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('pastoraldate')
                    ->native(false)
                    ->displayFormat('Y-m-d')
                    ->format('Y-m-d')
                    ->required(),
                Forms\Components\MorphToSelect::make('pastoralnotable')->label('Individual or Household')
                    ->types([
                        Forms\Components\MorphToSelect\Type::make(Individual::class)
                            ->titleAttribute('fullname'),
                        Forms\Components\MorphToSelect\Type::make(Household::class)
                            ->titleAttribute('addressee'),
                        ])
                    ->searchable()
                    ->model(Pastoralnote::class),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('details')
            ->columns([
                Tables\Columns\TextColumn::make('pastoraldate')->label('Date'),                
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('details'),
            ])
            ->defaultSort('pastoraldate','DESC')
            ->filters([
                //
            ])
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
