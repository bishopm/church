<?php

namespace Bishopm\Church\Filament\Clusters\Worship\Resources;

use Bishopm\Church\Filament\Clusters\Worship;
use Bishopm\Church\Filament\Clusters\Worship\Resources\SongResource\Pages;
use Bishopm\Church\Models\Service;
use Bishopm\Church\Models\Setitem;
use Bishopm\Church\Models\Song;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Form;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Hugomyb\FilamentMediaAction\Forms\Components\Actions\MediaAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Joaopaulolndev\FilamentPdfViewer\Forms\Components\PdfViewerField;
use Illuminate\Support\Str;
use Yaza\LaravelGoogleDriveStorage\Gdrive;

class SongResource extends Resource
{
    protected static ?string $model = Song::class;

    protected static ?string $navigationIcon = 'heroicon-o-speaker-wave';

    protected static ?string $cluster = Worship::class;

    protected static ?int $navigationSort = 2;

    public $record;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Add New Song')->columnSpanFull()->tabs([
                    Tab::make('Main')->columns(2)->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(191),
                        Forms\Components\Select::make('musictype')
                            ->label('Type')
                            ->default('contemporary')
                            ->options([
                                'archive' => 'Archive',
                                'contemporary' => 'Contemporary',
                                'hymn' => 'Hymn',
                            ])
                            ->default('contemporary')
                            ->required(),    
                        Forms\Components\TextInput::make('firstline')
                            ->required()
                            ->label('First line')
                            ->maxLength(255),
                        SpatieTagsInput::make('tags'),
                        Placeholder::make('Services')
                            ->key('servicePlaceholder')
                            ->label(function (Song $record = null): string {
                                if ($record){
                                    return 'Last used: ' . $record->lastused;
                                } else {
                                    return 'Last used: ';
                                }
                            })
                            ->hintActions(self::getServices())
                            ->columnSpanFull(),
                        PdfViewerField::make('file')
                            ->hiddenOn('create')
                            ->label('')
                            ->minHeight('80svh')
                            ->fileUrl(fn (Song $record) => url('/') . '/admin/reports/song/' . $record->id)
                            ->columnSpanFull(),
                    ]),
                    Tab::make('Details')->columns(2)->schema([
                        Forms\Components\TextInput::make('author')
                            ->maxLength(191),
                        Forms\Components\TextInput::make('tempo')
                            ->maxLength(191),
                        Forms\Components\TextInput::make('copyright')
                            ->maxLength(191),
                        Forms\Components\TextInput::make('key')
                            ->maxLength(191),
                        Forms\Components\TextInput::make('verseorder')
                            ->maxLength(191)
                            ->label('Verse order'),
                        Forms\Components\TextInput::make('tune')
                            ->maxLength(191),
                        Forms\Components\Textarea::make('lyrics')
                            ->required()
                            ->rows(20)
                            ->columnSpanFull(),
                        Forms\Components\Placeholder::make('openlp')
                            ->content(function (Get $get){
                                    $lyrics=$get('lyrics');
                                    $lyrics=preg_replace('/\[[^\]]*\]/', '', $lyrics);
                                    $lyrics=str_replace('{V','---[Verse:',$lyrics);
                                    $lyrics=str_replace('{C','---[Chorus:',$lyrics);
                                    $lyrics=str_replace('{P','---[Pre-Chorus:',$lyrics);
                                    $lyrics=str_replace('{B','---[Bridge:',$lyrics);
                                    $lyrics=str_replace('{T','---[Tag:',$lyrics);
                                    $lyrics=str_replace('}',']---',$lyrics);
                                    $lyrics=nl2br($lyrics);
                                    return new HtmlString($lyrics);
                                }
                            )
                            ->columnSpanFull(),
                    ]),
                    Tab::make('Media')->schema([     
                        Forms\Components\TextInput::make('audio')
                            ->suffixAction(MediaAction::make('playAudio')
                                ->icon('heroicon-m-musical-note')
                                ->media(fn (Get $get) => $get('audio'))
                        ),
                        Forms\Components\TextInput::make('video')
                            ->suffixAction(MediaAction::make('showVideo')
                                ->icon('heroicon-m-video-camera')
                                ->media(fn (Get $get) => $get('video'))
                        ),
                        Forms\Components\FileUpload::make('music')
                            ->label(function (Song $record){
                                if ($record->music<>""){
                                    $url=Storage::disk('google')->url($record->music);
                                    $url=str_replace('uc?id=','file/d/',$url);
                                    $url=str_replace('&export=media','/view',$url);    
                                    return new HtmlString("<a target='_blank' href='" . $url . "'>Click here to open music</a>");
                                } else {
                                    return "Music";
                                }
                                return $url;
                            })
                            ->directory('songs')
                            ->downloadable()
                            ->disk('google'),
                    ]),
                    Tab::make('History')->schema([
                        Forms\Components\Placeholder::make('history')->label('')
                        ->content(function (Song $record = null) {
                            if ($record){
                                $allplays=Service::whereHas('setitems', 
                                    function($q) use ($record) { 
                                        $q->where('setitemable_id',$record->id)
                                        ->where('setitemable_type','song'); 
                                    })
                                    ->where('servicedate','<',date('Y-m-d'))->orderBy('servicedate','DESC')->get();
                                $history=array();
                                foreach ($allplays as $play){
                                    $history[$play->servicetime][]=date('Y-m-d',strtotime($play->servicedate));
                                }
                                ksort($history);
                                $period=date('Y-m-d',strtotime('4 months ago'));
                                $histarray=array();
                                foreach ($history as $stime=>$hist){
                                    asort($hist);
                                    $histarray[$stime]['latest']=$hist[0];
                                    $histarray[$stime]['recent']=0;
                                    $histarray[$stime]['total']=0;
                                    foreach ($hist as $hh){
                                        if ($hh>$period){
                                            $histarray[$stime]['recent']++;
                                        }
                                        $histarray[$stime]['total']++;
                                    }
                                }
                                $historytext="";
                                foreach ($histarray as $service=>$val){
                                    $historytext.="<b>" . $service . "</b>: Sung ";
                                    if ($val['total']==1) {
                                        $historytext.= "once in total on " . $val['latest'];
                                    } else {
                                        $historytext.= $val['total'] . " times in total (";
                                        if ($val['recent'] ==1 ){
                                            $historytext.= "once in the last four months, on " . $val['latest'] . ")<br>";
                                        } elseif ($val['recent'] > 1) {
                                            $historytext.= $val['recent'] . " times in the last four months) and most recently on " . $val['latest'] . "<br>";
                                        }  elseif ($val['recent'] == 1) {
                                            $historytext.= $val['recent'] . " time in the last four months) and most recently on " . $val['latest'] . "<br>";
                                        } else {
                                            $historytext= substr($historytext,0,-1) . "and most recently on " . $val['latest'] . "<br>";
                                        }
                                    }
                                }
                                return new HtmlString($historytext);
                            } else {
                                return " ";
                            }
                        })
                    ]),
                ]),
            ]);
    }

    public static function getServices (){
        $serviceActions=[];
        $songid = request()->route()->parameter('record');
        $services=Service::with('setitems')->where('servicedate','>=',date('Y-m-d'))->get();
        foreach ($services as $service){
            $serviceActions[]=Action::make('Add' . $service->servicedate . $service->servicetime)
            ->label($service->servicedate . ' (' . $service->servicetime . ')')->button()
            ->action(function ($record) use ($service) {
                self::addToService($service->id,$record->id,count($service->setitems));
                return redirect()->route('filament.admin.worship.resources.songs.edit', ['record' => $record->id]);
            })
            ->icon('heroicon-o-plus')
            ->hidden(function () use ($service,$songid){
                $check=Setitem::where('service_id',$service->id)->where('setitemable_id',$songid)->where('setitemable_type','song')->first();
                if (!$check){
                    return false;
                } else {
                    return true;
                }
            });
        }
        return $serviceActions;
    }

    public static function addToService($service,$song,$order){
        Setitem::create([
            'service_id'=>$service,
            'setitemable_type'=>'song',
            'setitemable_id'=>$song,
            'sortorder'=>$order
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(['title','lyrics']),
                Tables\Columns\TextColumn::make('lastused')
                    ->label('Last used'),
                Tables\Columns\TextColumn::make('musictype')->label('Type')
                    ->searchable()
                    ->formatStateUsing(fn (string $state) => Str::title($state)),
                Tables\Columns\TextColumn::make('tags.name')
                    ->badge()
                    ->forceSearchCaseInsensitive(true)
                    ->searchable()
            ])
            ->filters([
                SelectFilter::make('musictype')->label('')
                ->options([
                    'archive' => 'Archive',
                    'contemporary' => 'Contemporary',
                    'hymn' => 'Hymn'
                ]),
                Filter::make('hide_archive')
                ->query(fn (Builder $query): Builder => $query->where('musictype', '<>', 'archive'))
                ->default()
            ])
            ->defaultSort('title','ASC')
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
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
            'index' => Pages\ListSongs::route('/'),
            'create' => Pages\CreateSong::route('/create'),
            'edit' => Pages\EditSong::route('/{record}/edit'),
        ];
    }
}
