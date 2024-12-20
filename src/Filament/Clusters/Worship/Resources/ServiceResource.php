<?php

namespace Bishopm\Church\Filament\Clusters\Worship\Resources;

use Bishopm\Church\Filament\Clusters\Worship;
use Bishopm\Church\Filament\Clusters\Worship\Resources\ServiceResource\Pages;
use Bishopm\Church\Models\Service;
use Bishopm\Church\Models\Song;
use Bishopm\Church\Models\Prayer;
use Bishopm\Church\Models\Setitem;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $cluster = Worship::class;

    public ?array $data;

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        $isCreate = $form->getOperation() === "create";
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\DatePicker::make('servicedate')
                    ->native(false)
                    ->displayFormat('Y-m-d')
                    ->format('Y-m-d')
                    ->label('Service date')
                    ->default(date('Y-m-d',strtotime('Sunday')))
                    ->live(onBlur: true)
                    ->required(),
                Forms\Components\Select::make('servicetime')
                    ->required()
                    ->label('Service time')
                    ->options(function (Get $get) use ($isCreate) {
                        $sd=substr($get('servicedate'),0,10);
                        $servicetimes = setting('general.services');
                        if ($isCreate){
                            $services = Service::where('servicedate',$sd)->get();
                            foreach ($services as $service) {
                                if (($key = array_search($service->servicetime, $servicetimes)) !== false) {
                                    unset($servicetimes[$key]);
                                }
                            }
                        }
                        $sarray=array();
                        foreach ($servicetimes as $st){
                            $sarray[$st]=$st;
                        }
                        return $sarray;
                    })
                    ->placeholder(''),
                Forms\Components\TextInput::make('reading')
                    ->default(function (Get $get) {
                        $sd=substr($get('servicedate'),0,10);
                        $service = Service::where('servicedate',$sd)->first();
                        if ($service){
                            return $service->reading;
                        }
                    })
                    ->required()
                    ->maxLength(191),
                Forms\Components\Select::make('series_id')
                    ->placeholder('')
                    ->relationship(name: 'series', titleAttribute: 'series')
                    ->default(function (Get $get) {
                        $sd=substr($get('servicedate'),0,10);
                        $service = Service::where('servicedate',$sd)->first();
                        if ($service) {
                            return $service->series_id;
                        }
                    }),
                Forms\Components\Repeater::make('setitems')
                    ->live()
                    ->hiddenOn('create')
                    ->relationship('setitems')
                    ->label('')
                    ->columnSpan(2)
                    ->schema([])
                    ->view('church::components.set-item')
                    ->reorderableWithDragAndDrop(true)
                    ->orderColumn('sortorder')
                    ->addActionLabel('Add new set item')
                    ->itemLabel(function (array $state){
                        if ($state['note']) {
                            return $state['note'];
                        } else {
                            if ($state['setitemable_id']){
                                $setitem=Setitem::with('setitemable')->where('id',$state['id'])->first();
                                return $setitem->setitemable->title;
                            } else {
                                dd($state);
                            }
                        }
                    })
                    ->deleteAction(
                        function (Action $action) {
                            return $action
                                ->after(function ($state, array $arguments) {
                                    $id=substr($arguments['item'],7);
                                    Setitem::find($id)->delete();
                                });
                        })
                    ->addAction(function ($action) {
                        return $action->form([
                            Forms\Components\MorphToSelect::make('setitemable')->label('Song or prayer')
                                ->types([
                                    Forms\Components\MorphToSelect\Type::make(Song::class)
                                        ->titleAttribute('title'),
                                    Forms\Components\MorphToSelect\Type::make(Prayer::class)
                                        ->titleAttribute('title'),
                                    ])
                            ->searchable()
                            ->model(Setitem::class),
                            Forms\Components\TextInput::make('note'),
                            ])
                            ->after(function ($data, Get $get, Repeater $component) {
                                $component->state(function ($state) use ($data, $get) {
                                    $setitems = collect($state);
                                    $id = $get('id');
                                    $ndx = count($setitems);
                                    if ($data['setitemable_type']=="song"){
                                        $song = Song::find($data['setitemable_id']);
                                        if (is_null($data['note'])){
                                            $data['note']=$song->tune;
                                        } else {
                                            $data['note']=$data['note'] . " " . $song->tune;
                                        }
                                    }
                                    $si = Setitem::create([
                                        'service_id' => $id,
                                        'setitemable_id' => $data['setitemable_id'],
                                        'setitemable_type' => $data['setitemable_type'],
                                        'note' => $data['note'],
                                        'sortorder' => $ndx
                                    ]);
                                    $setitems['record-' . $si->id]=array(
                                        'id' => $si->id,
                                        'service_id' => $si->service_id,
                                        'setitemable_id' => $si->setitemable_id,
                                        'setitemable_type' => $si->setitemable_type,
                                        'note' => $si->setitemable->title,
                                        'sortorder' => $si->sortorder,
                                        'extra' => null
                                    );
                                    foreach ($setitems as $i=>$setitem){
                                        if (substr($i,0,6)<>"record"){
                                            unset($setitems[$i]);
                                        }
                                    }
                                    return $setitems->toArray();
                                });
                            });
                    })->extraItemActions([
                        Action::make('viewItem')
                        ->icon('heroicon-o-link')
                        ->hidden(function (array $arguments, Forms\Components\Repeater $component) {
                            $si=$component->getRawItemState($arguments['item']);
                            if ($si['setitemable_type']){
                                return false;
                            } elseif ($si['note']=="Bible reading"){
                                return false;
                            } else  {
                                return true;
                            }
                        })
                        ->url(function (array $arguments, Forms\Components\Repeater $component) {
                            $si=$component->getRawItemState($arguments['item']);
                            if (!$si['setitemable_type']){
                                if ($si['note']=="Bible reading"){ 
                                    $service=Service::find($si['service_id']);
                                    return "http://biblegateway.com/passage/?search=" . urlencode($service->reading) . "&version=GNT";
                                } else {
                                    return "";
                                }
                            } else {
                                if ($si['setitemable_type']=="song") {
                                    return route('filament.admin.worship.resources.songs.edit',['record'=>$si['setitemable_id']]);
                                } else {
                                    return route('filament.admin.worship.resources.prayers.edit',['record'=>$si['setitemable_id']]);
                                }
                            }
                        })
                        ->openUrlInNewTab(),
                        Action::make('editSetitem')
                        ->label('Edit set item')
                        ->tooltip('Edit')
                        ->icon('heroicon-o-pencil-square')
                        ->fillForm(function (array $arguments, Forms\Components\Repeater $component) {
                            return $component->getRawItemState($arguments['item']);
                          })
                        ->form([
                            Forms\Components\Placeholder::make('setitemable_type')->label('')->content(
                                function (Get $get){
                                    $id = $get('id');
                                    $setitem=\Bishopm\Church\Models\Setitem::with('setitemable')->where('id',$id)->first();
                                    if (isset($setitem->setitemable)){
                                        return $setitem->setitemable->title;
                                    }
                                }
                            ),
                            Forms\Components\TextInput::make('note')
                                ->label('Details')
                                ->default(function (Get $get){
                                    return $get('note');
                                }),
                            Forms\Components\Hidden::make('id')
                                ->default(function (Get $get){
                                    return $get('id');
                                }),
                        ])
                        ->action(function (array $data, Setitem $setitem): void {
                            $setitem=Setitem::find($data['id']);
                            $setitem->note=$data['note'];
                            $setitem->save();
                        })
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('servicedate')->label('Date of service')
                    ->date('Y-m-d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('servicetime')->label('Time')
                    ->searchable(),
                Tables\Columns\TextColumn::make('reading')
                    ->searchable(),
                Tables\Columns\TextColumn::make('series.series')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort(fn ($query) => $query->orderBy('servicedate', 'desc')->orderBy('servicetime', 'asc'))
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSets::route('/'),
            'create' => Pages\CreateSet::route('/create'),
            'edit' => Pages\EditSet::route('/{record}/edit'),
        ];
    }
}
