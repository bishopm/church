<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources\FormResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class FormitemsRelationManager extends RelationManager
{
    protected static string $relationship = 'formitems';

    protected static ?string $modelLabel = 'form item';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('itemtype')
                    ->options([
                        'cell'=>'Block',
                        'image'=>'Image',
                        'line'=>'Line',
                        'text'=>'Text',
                    ])
                    ->columnSpanFull()
                    ->live()
                    ->required(),
                Forms\Components\TextInput::make('x')
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell","line","image","text"])),
                Forms\Components\TextInput::make('y')
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell","line","image","text"])),
                Forms\Components\TextInput::make('width')
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell","line","image","text"])),
                Forms\Components\TextInput::make('height')
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell","line","image","text"])),
                Forms\Components\TextInput::make('text')
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell","line","image","text"])),
                Forms\Components\Toggle::make('border')
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell","line","image","text"])),
                Forms\Components\Select::make('alignment')
                    ->options([
                        'L'=>'Left',
                        'C'=>'Centre',
                        'R'=>'Right'
                    ])
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell","line","image","text"])),
                Forms\Components\Toggle::make('fill')
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell","line","image","text"])),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('itemdata')
            ->columns([
                Tables\Columns\TextColumn::make('itemtype'),
                Tables\Columns\TextColumn::make('itemdata'),
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
