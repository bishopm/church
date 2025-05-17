<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources\FormResource\RelationManagers;

use Bishopm\Church\Models\Formitem;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

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
                    ->afterStateHydrated(function ($record, Set $set) {
                        if ($record){
                            $props=json_decode($record->itemdata);
                            foreach ($props as $fld=>$prop){
                                $set($fld,$prop);
                            }
                        }
                    })
                    ->default('cell')
                    ->selectablePlaceholder(false)
                    ->columnSpanFull()
                    ->live()
                    ->required(),
                Forms\Components\TextInput::make('x')->numeric()->default(0)
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell","line","image","text"])),
                Forms\Components\TextInput::make('y')->numeric()->default(0)
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell","line","image","text"])),
                Forms\Components\TextInput::make('width')->numeric()->default(0)
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell","line","image"])),
                Forms\Components\TextInput::make('height')->numeric()->default(0)
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell","line","image"])),
                Forms\Components\TextInput::make('text')
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell","line","image","text"])),
                Forms\Components\TextInput::make('font')
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell","line","image","text"])),
                Forms\Components\Toggle::make('border')
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell","line","image"])),
                Forms\Components\TextInput::make('rounded')->numeric()->default(0)->label('Rounded box corner angle')
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell"])),
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
                    })->after(function (RelationManager $livewire) {
                        $livewire->dispatch('form-items-updated');
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('Edit form item')
                    ->using(function ($record,array $data, RelationManager $livewire) {
                        $itemtype=$data['itemtype'];
                        unset($data['itemtype']);
                        $itemdata=json_encode($data);
                        $record->itemdata=$itemdata;
                        $record->itemtype=$itemtype;
                        $record->save();
                        return $record;
                    })->after(function (RelationManager $livewire) {
                        $livewire->dispatch('form-items-updated');
                    }),
                Tables\Actions\DeleteAction::make()->after(function (RelationManager $livewire) {
                        $livewire->dispatch('form-items-updated');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
