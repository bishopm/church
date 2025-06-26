<?php

namespace Bishopm\Church\Filament\Clusters\Resources\Resources\CardsResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CarditemsRelationManager extends RelationManager
{
    protected static string $relationship = 'carditems';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('itemtype')->label('Item type')
                    ->options([
                        'button'=>'Action button',
                        'image'=>'Image',
                        'text'=>'Text'
                    ])
                    ->afterStateHydrated(function ($record, Set $set) {
                        if ($record){
                            $props=json_decode($record->properties);
                            foreach ($props as $fld=>$prop){
                                $set($fld,$prop);
                            }
                        }
                    })
                    ->default('text')
                    ->selectablePlaceholder(false)
                    ->columnSpanFull()
                    ->live()
                    ->required(),                
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id'),
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
