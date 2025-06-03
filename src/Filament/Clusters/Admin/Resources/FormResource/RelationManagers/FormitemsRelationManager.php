<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources\FormResource\RelationManagers;

use Bishopm\Church\Models\Formitem;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
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
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell","line","image","text"]))
                    ->default(function (RelationManager $livewire){
                        $items=$livewire->ownerRecord->formitems;
                        foreach ($items as $item){
                            if ($item->itemorder==count($items)){
                                $props=json_decode($item->itemdata);
                                return $props->x;
                            }
                        }
                    }),
                Forms\Components\TextInput::make('y')->numeric()->default(0)
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell","line","image","text"]))
                    ->default(function (RelationManager $livewire){
                        $items=$livewire->ownerRecord->formitems;
                        foreach ($items as $item){
                            if ($item->itemorder==count($items)){
                                $props=json_decode($item->itemdata);
                                return $props->y+$livewire->ownerRecord->lineheight;
                            }
                        }
                    }),
                Forms\Components\TextInput::make('x2')->numeric()->default(0)
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["line"])),
                Forms\Components\TextInput::make('y2')->numeric()->default(0)
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["line"])),
                Forms\Components\TextInput::make('width')->numeric()->default(0)
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell","image"])),
                Forms\Components\TextInput::make('height')->numeric()->default(0)
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell","image"])),
                Forms\Components\TextInput::make('text')
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell","text"])),
                Forms\Components\Select::make('font')
                    ->afterStateHydrated(function (Select $component, ?string $state){
                        if (!$state){
                            $component->state($this->ownerRecord->font);
                        }
                    })
                    ->options([
                        'Arial'=>'Arial',
                        'Courier'=>'Courier',
                        'Times'=>'Times New Roman'
                    ])
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell","text"])),
                Forms\Components\TextInput::make('fontsize')
                    ->afterStateHydrated(function (TextInput $component, ?string $state){
                        if (!$state){
                            $component->state($this->ownerRecord->fontsize);
                        }
                    })
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell","text"]))->label('Font size')->numeric(),
                Forms\Components\Select::make('fontstyle')->label('Font style')
                    ->selectablePlaceholder(false)
                    ->options([
                        ''=>'Normal',
                        'B'=>'Bold',
                        'I'=>'Italic'
                    ])
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell","text"])),
                Forms\Components\Toggle::make('border')
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell"])),
                Forms\Components\TextInput::make('rounded')->numeric()->default(0)->label('Rounded box corner angle')
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell"])),
                Forms\Components\Select::make('file')->label('Image filename')
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["image"]))
                    ->options(function (){
                        $files=scandir(public_path("/church/images/"));
                        $options=array();
                        foreach ($files as $file){
                            if (strpos($file,'.')>2){
                                $options[$file]=$file;
                            }
                        }
                        return $options;
                    }),
                Forms\Components\Select::make('alignment')
                    ->selectablePlaceholder(false)
                    ->options([
                        'L'=>'Left',
                        'C'=>'Centre',
                        'R'=>'Right'
                    ])
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell"])),
                Forms\Components\Toggle::make('fill')
                    ->visible(fn (Get $get) => in_array($get('itemtype'),["cell"])),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('itemdata')
            ->columns([
                Tables\Columns\TextColumn::make('itemorder')->label('No'),
                Tables\Columns\TextColumn::make('row')->label('Row'),
                Tables\Columns\TextColumn::make('details')->label('Details'),
            ])
            ->filters([
                //
            ])
            ->reorderable('itemorder')
            ->defaultSort('itemorder')
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
                            'itemdata' => $itemdata,
                            'itemorder' => 1+count($livewire->ownerRecord->formitems)
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
