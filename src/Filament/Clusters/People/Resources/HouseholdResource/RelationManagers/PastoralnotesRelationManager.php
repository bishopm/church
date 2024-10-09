<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources\HouseholdResource\RelationManagers;

use Bishopm\Church\Models\Household;
use Bishopm\Church\Models\Individual;
use Bishopm\Church\Models\Pastor;
use Bishopm\Church\Models\Pastoralnote;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PastoralnotesRelationManager extends RelationManager
{
    protected static string $relationship = 'pastoralnotes';

    protected static ?string $modelLabel = 'pastoral note';

    protected static ?string $title = 'Pastoral notes';

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
                    ->format('Y-m-d'),
                Forms\Components\MorphToSelect::make('pastoralnotable')->label('Individual or Household')
                    ->types([
                        Forms\Components\MorphToSelect\Type::make(Individual::class)
                            ->titleAttribute('fullname'),
                        Forms\Components\MorphToSelect\Type::make(Household::class)
                            ->titleAttribute('addressee'),
                        ])
                    ->searchable()
                    ->model(Pastoralnote::class),
                Forms\Components\Select::make('pastor_id')
                    ->label('Pastor')
                    ->options(function () {
                        $pastors = Pastor::with('individual')->get()->sortBy('individual.firstname');
                        $parray=[];
                        foreach ($pastors as $pastor){
                            $parray[$pastor->id] = $pastor->individual->firstname . " " . $pastor->individual->surname;
                        }
                        return $parray;
                    })
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('details')
            ->columns([
                Tables\Columns\TextColumn::make('pastoraldate')->label('Date'),
                Tables\Columns\TextColumn::make('details'),
                Tables\Columns\TextColumn::make('pastor.individual.fullname')->label('Pastor'),
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
