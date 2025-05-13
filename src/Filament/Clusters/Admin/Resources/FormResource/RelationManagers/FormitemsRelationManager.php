<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources\FormResource\RelationManagers;

use Bishopm\Church\Models\Formitem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Livewire\Livewire;

class FormitemsRelationManager extends RelationManager
{
    protected static string $relationship = 'formitems';

    protected static ?string $modelLabel = 'form item';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('itemtype')->label('Item type')
                    ->options([
                        'cell'=>'Text box',
                        'image'=>'Image',
                        'line'=>'Line',
                        'text'=>'Text',
                    ])
                    ->default('cell')
                    ->selectablePlaceholder(false)
                    ->columnSpanFull()
                    ->live()
                    ->required(),
                Forms\Components\TextInput::make('x')
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell","line","image","text"])),
                Forms\Components\TextInput::make('y')
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell","line","image","text"])),
                Forms\Components\TextInput::make('width')
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell","line","image"])),
                Forms\Components\TextInput::make('height')
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell","line","image"])),
                Forms\Components\TextInput::make('text')
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell","line","image","text"])),
                Forms\Components\Toggle::make('border')
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell","line","image"])),
                Forms\Components\Select::make('alignment')
                    ->selectablePlaceholder(false)
                    ->options([
                        'L'=>'Left',
                        'C'=>'Centre',
                        'R'=>'Right'
                    ])
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell","line","image"])),
                Forms\Components\Toggle::make('fill')
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell","line","image"])),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('itemdata')
            ->columns([
                Tables\Columns\TextColumn::make('itemtype')->label('Type'),
                Tables\Columns\TextColumn::make('itemdata')->label('Properties'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->using(function (array $data, RelationManager $livewire) {
                        $itemtype=$data['itemtype'];
                        $parent = $livewire->getOwnerRecord()->id;
                        unset($data['itemtype']);
                        $itemdata=json_encode($data);
                        $new=Formitem::create([
                            'form_id' =>  $parent,
                            'itemtype' => $itemtype,
                            'itemdata' => $itemdata
                        ]);
                        return $new;
                    })->after(function () {
                        Livewire::dispatchBrowserEvent('formitem-created');
                    }),
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
