<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources\HouseholdResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AnniversariesRelationManager extends RelationManager
{
    protected static string $relationship = 'anniversaries';

    protected static ?string $title = 'Anniversaries';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('details')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('anniversarydate')->label('Date')
                    ->native(false)
                    ->displayFormat('Y-m-d')
                    ->format('Y-m-d'),
                Forms\Components\Select::make('anniversarytype')
                    ->options([
                        'Baptism' => 'Baptism',
                        'Death' => 'Death',
                        'Wedding' => 'Wedding'
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('details')
            ->columns([
                Tables\Columns\TextColumn::make('details'),
                Tables\Columns\TextColumn::make('anniversarydate')->label('Date'),
                Tables\Columns\TextColumn::make('anniversarytype')->label('Anniversary type')
            ])
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
