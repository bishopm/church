<?php

namespace Bishopm\Church\Filament\Clusters\Worship\Resources;

use Bishopm\Church\Filament\Clusters\Worship;
use Bishopm\Church\Filament\Clusters\Worship\Resources\ServiceResource\Pages;
use Bishopm\Church\Models\Person;
use Bishopm\Church\Models\Service;
use Bishopm\Church\Models\Song;
use Bishopm\Church\Models\Prayer;
use Bishopm\Church\Models\Setitem;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\SpatieTagsInput;
use Hugomyb\FilamentMediaAction\Forms\Components\Actions\MediaAction;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $cluster = Worship::class;

    public ?array $data;

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        $isCreate = $form->getOperation() === "create";
        return $form
            ->statePath('data')
            ->schema([
                Tabs::make('Add New Service')->columnSpanFull()->tabs([
                    Tab::make('Order of service')->columns(2)->schema([
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
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, Get $get, $state){
                                if ($state==setting('worship.live_streamed_service')){
                                    $set('livestream',true);
                                }
                                $url="https://methodist.church.net.za/preacher/" . setting('services.society_id') . "/" . $state . "/" . substr($get('servicedate'),0,10);
                                $response=Http::get($url);
                                $fullname=$response->body();
                                $preacher=Person::where(DB::raw('concat(firstname," ",surname)') , '=' , $fullname)->first();
                                if ($preacher){
                                    $set('person_id',$preacher->id);  
                                }
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
                                    Forms\Components\Select::make('setitemable_type')->label('Item type')
                                        ->options([
                                            'song' => 'Song',
                                            'prayer' => 'Liturgy',
                                            'other' => 'Other'
                                        ])
                                        ->default('song')
                                        ->live()
                                        ->selectablePlaceholder(false)
                                        ->afterStateUpdated(function (Set $set) {
                                            $set('setitemable_id',null);
                                        }),
                                    Forms\Components\Select::make('setitemable_id')
                                        ->label('Item')
                                        ->searchable()
                                        ->selectablePlaceholder(false)
                                        ->options(function (Get $get) {
                                            $id=$get('setitemable_type');
                                            if ($id=='song'){
                                                return Song::orderBy('title')->get()->pluck('title', 'id')->toArray();
                                            } elseif ($id=='prayer'){
                                                return Prayer::orderBy('title')->get()->pluck('title', 'id')->toArray();
                                            } else {
                                                $dat=setting('worship.set_items');
                                                asort($dat);
                                                return array_combine($dat,$dat);
                                            }
                                    }),
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
                                            } elseif ($data['setitemable_type']=="other"){
                                                $data['note']=$data['setitemable_id'];
                                                $data['setitemable_type']=null;
                                                $data['setitemable_id']=null;
                                            }
                                            $si = Setitem::create([
                                                'service_id' => $id,
                                                'setitemable_id' => $data['setitemable_id'],
                                                'setitemable_type' => $data['setitemable_type'],
                                                'note' => $data['note'],
                                                'sortorder' => $ndx
                                            ]);
                                            if (isset($si->setitemable->title)){
                                                $setitems['record-' . $si->id]=array(
                                                    'id' => $si->id,
                                                    'service_id' => $si->service_id,
                                                    'setitemable_id' => $si->setitemable_id,
                                                    'setitemable_type' => $si->setitemable_type,
                                                    'note' => $si->setitemable->title,
                                                    'sortorder' => $si->sortorder,
                                                    'extra' => null
                                                );
                                            } else {
                                                $setitems['record-' . $si->id]=array(
                                                    'id' => $si->id,
                                                    'service_id' => $si->service_id,
                                                    'setitemable_id' => $si->setitemable_id,
                                                    'setitemable_type' => $si->setitemable_type,
                                                    'note' => $data['note'],
                                                    'sortorder' => $si->sortorder,
                                                    'extra' => null
                                                );
                                            }
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
                    ]),
                    Tab::make('Sermon')->columns(2)->schema([
                        Forms\Components\Select::make('person_id')
                            ->label('Preacher')
                            ->relationship(
                                name: 'person',
                                modifyQueryUsing: fn (Builder $query) => $query->orderBy('firstname')->orderBy('surname'),
                            )
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->firstname} {$record->surname}")
                            ->createOptionForm([
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('firstname')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('surname')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('bio')
                                    ->columnSpanFull(),
                                Forms\Components\FileUpload::make('image')
                                    ->image(),
                                Forms\Components\TextInput::make('role')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Toggle::make('active'),
                            ]),
                        Forms\Components\TextInput::make('sermon_title')
                            ->label('Sermon title')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('video')
                            ->suffixAction(MediaAction::make('showVideo')
                                ->icon('heroicon-m-video-camera')
                                ->media(function (Get $get){
                                    return "https://youtube.com/watch?v=" . $get('video');
                                })
                        ),
                        Forms\Components\TextInput::make('audio')
                            ->suffixAction(MediaAction::make('playAudio')
                                ->icon('heroicon-m-musical-note')
                                ->media(fn (Get $get) => $get('audio'))
                        ),
                        SpatieTagsInput::make('tags'),
                        Forms\Components\Toggle::make('livestream')->label('Service will be live-streamed'),
                        Forms\Components\Toggle::make('published')        
                    ])
                ])
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
