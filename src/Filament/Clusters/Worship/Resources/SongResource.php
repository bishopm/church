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
use Filament\Forms\Form;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Get;
use Filament\Forms\Set;
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

    public array $allkeys = [
                                'A' => 'A',
                                'A#/Bb' => 'A#/Bb',
                                'B' => 'B',
                                'C' => 'C',
                                'C#/Db' => 'C#/Db',
                                'D' => 'D',
                                'D#/Eb' => 'D#/Eb',
                                'E' => 'E',
                                'F' => 'F',
                                'F#/Gb' => 'F#/Gb',
                                'G' => 'G',
                                'G#/Ab' => 'G#/Ab'
                            ];

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Add New Song')->columnSpanFull()->tabs([
                    Tab::make('Main')->columns(2)->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(191),
                        Forms\Components\TextInput::make('author')
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
                        Forms\Components\Select::make('tags')
                            ->relationship('tags','name',modifyQueryUsing: fn (Builder $query) => $query->where('type','song'))
                            ->multiple()
                            ->createOptionForm([
                                Forms\Components\Grid::make()
                                    ->columns(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                            ->required(),
                                        Forms\Components\TextInput::make('type')
                                            ->default('song')
                                            ->readonly()
                                            ->required(),
                                        Forms\Components\TextInput::make('slug')
                                            ->required(),
                                    ])
                            ]),
                        Placeholder::make('Services')
                            ->key('servicePlaceholder')
                            ->label(function (Song $record = null): string {
                                if ($record){
                                    return 'Last used: ' . $record->lastused;
                                } else {
                                    return 'Last used: ';
                                }
                            })
                            ->hintActions(self::getServices()),
                        PdfViewerField::make('file')
                            ->hiddenOn('create')
                            ->label('')
                            ->minHeight('80svh')
                            ->fileUrl(fn (Song $record) => url('/') . '/admin/reports/song/' . $record->id)
                            ->columnSpanFull(),
                    ]),
                    Tab::make('Details')->columns(2)->schema([
                        Forms\Components\TextInput::make('tempo')
                            ->maxLength(191),
                        Forms\Components\TextInput::make('copyright')
                            ->maxLength(191),
                        Forms\Components\Select::make('key')
                            ->options([
                                'A' => 'A',
                                'A#/Bb' => 'A#/Bb',
                                'B' => 'B',
                                'C' => 'C',
                                'C#/Db' => 'C#/Db',
                                'D' => 'D',
                                'D#/Eb' => 'D#/Eb',
                                'E' => 'E',
                                'F' => 'F',
                                'F#/Gb' => 'F#/Gb',
                                'G' => 'G',
                                'G#/Ab' => 'G#/Ab'
                            ]),
                        Forms\Components\TextInput::make('verseorder')
                            ->maxLength(191)
                            ->label('Verse order'),
                        Forms\Components\TextInput::make('tune')
                            ->maxLength(191),
                        Forms\Components\TextInput::make('bible')->label('Bible reference')
                            ->maxLength(191),
                        \Filament\Forms\Components\Actions::make([
                            Action::make('transposeDown')
                                ->label('Transpose Down')
                                ->button()
                                ->color('primary')
                                ->action(function (Set $set, Get $get) {
                                    $currentKey = $get('key');
                                    $lyrics = $get('lyrics');
                                    $keys=['A','A#/Bb','B','C','C#/Db','D','D#/Eb','E','F','F#/Gb','G','G#/Ab'];
                                    if (in_array($currentKey, $keys)) {
                                        $currentIndex = array_search($currentKey, $keys);
                                        $newIndex = ($currentIndex - 1 + count($keys)) % count($keys);
                                        $set('key', $keys[$newIndex]);
                                    }
                                    $newlyrics = self::transpose('down',$lyrics);
                                    $set('lyrics', $newlyrics);
                                })
                                ->icon('heroicon-m-arrow-down'),
                            Action::make('transposeUp')
                                ->label('Transpose Up')
                                ->button()
                                ->color('primary')
                                ->action(function (Set $set, Get $get) {
                                    $currentKey = $get('key');
                                    $lyrics = $get('lyrics');
                                    $keys=['A','A#/Bb','B','C','C#/Db','D','D#/Eb','E','F','F#/Gb','G','G#/Ab'];
                                    if (in_array($currentKey, $keys)) {
                                        $currentIndex = array_search($currentKey, $keys);
                                        $newIndex = ($currentIndex + 1) % count($keys);
                                        $set('key', $keys[$newIndex]);
                                    }
                                    $newlyrics = self::transpose('up',$lyrics);
                                    $set('lyrics', $newlyrics);
                                })
                                ->icon('heroicon-m-arrow-up')
                        ]),
                        Forms\Components\Textarea::make('lyrics')
                            ->label('Lyrics ({} for sections, [] for chords)')
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
                                ->media(function (Get $get){
                                    return "https://youtube.com/watch?v=" . $get('video');
                                })
                        ),
                        Forms\Components\FileUpload::make('music')
                            ->hiddenOn('create')
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

    public static function transpose(string $updown, string $lyrics): string { 
        // Normalize direction
        $dir = (strtolower($updown) === 'down') ? -1 : 1;

        // Use sharps internally
        $notes = ['C','C#','D','D#','E','F','F#','G','G#','A','A#','B'];

        // Enharmonic normalization (flats → sharps + accidentals)
        $enharmonics = [
            'Db' => 'C#', 'Eb' => 'D#', 'Gb' => 'F#', 'Ab' => 'G#', 'Bb' => 'A#',
            'Cb' => 'B',  'Fb' => 'E',  'E#' => 'F',  'B#' => 'C',
        ];

        $transposeNote = function (string $note) use ($dir, $notes, $enharmonics): string {
            if (isset($enharmonics[$note])) {
                $note = $enharmonics[$note];
            }
            $i = array_search($note, $notes, true);
            if ($i === false) {
                return $note; // unknown root; leave unchanged
            }
            $i = ($i + $dir) % 12;
            if ($i < 0) $i += 12;
            return $notes[$i];
        };

        // Match chords inside [ ... ]
        return preg_replace_callback('/\[([^\]]+)\]/', function ($m) use ($transposeNote) {
            $sym = trim($m[1]);

            // Root + optional suffix + optional /Bass
            // Suffix can contain digits, words, parentheses, #, b, +, - etc.
            if (!preg_match('/^([A-G][#b]?)([^\/]*)(?:\/([A-G][#b]?))?$/', $sym, $p)) {
                return $m[0]; // Not a chord, leave unchanged
            }

            $root   = $p[1];
            $suffix = $p[2] ?? '';
            $bass   = $p[3] ?? null;

            $rootT = $transposeNote($root);
            $bassT = $bass !== null ? $transposeNote($bass) : null;

            return '[' . $rootT . $suffix . ($bassT ? '/' . $bassT : '') . ']';
        }, $lyrics);
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
                    ->searchable(['title','lyrics','author']),
                Tables\Columns\TextColumn::make('lastused')
                    ->label('Last used'),
                Tables\Columns\IconColumn::make('musictype')->label('Type')
                    ->icon(fn (string $state): string => match ($state) {
                        'archive' => 'heroicon-o-archive-box-x-mark',
                        'hymn' => 'heroicon-o-building-library',
                        'contemporary' => 'heroicon-o-musical-note',
                    }),
                Tables\Columns\IconColumn::make('music')
                    ->boolean(),
                Tables\Columns\TextColumn::make('key')
                    ->label('Key'),
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
