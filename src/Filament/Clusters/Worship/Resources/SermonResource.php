<?php

namespace Bishopm\Church\Filament\Clusters\Worship\Resources;

use Bishopm\Church\Filament\Clusters\Worship;
use Bishopm\Church\Filament\Clusters\Worship\Resources\SermonResource\Pages;
use Bishopm\Church\Filament\Clusters\Worship\Resources\SermonResource\RelationManagers;
use Bishopm\Church\Models\Person;
use Bishopm\Church\Models\Sermon;
use Bishopm\Church\Models\Series;
use Bishopm\Church\Models\Service;
use Filament\Forms;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Hugomyb\FilamentMediaAction\Forms\Components\Actions\MediaAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SermonResource extends Resource
{
    protected static ?int $navigationSort = 6;
    
    protected static ?string $model = Sermon::class;

    protected static ?string $navigationIcon = 'heroicon-o-microphone';

    protected static ?string $cluster = Worship::class;

    public $record;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('service_id')
                    ->label('Service')
                    ->relationship(
                        name: 'service',
                        modifyQueryUsing: fn (Builder $query, Sermon $record) => $query->doesnthave('sermon')->orWhere('id',$record->service_id)->orderBy('servicedate','DESC'),
                    )
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->servicedate} ({$record->servicetime})")
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, $state){
                        $service=Service::find($state);
                        $url="https://methodist.church.net.za/preacher/" . setting('services.society_id') . "/" . $service->servicetime . "/" . $service->servicedate;
                        $response=Http::get($url);
                        $fullname=$response->body();
                        $preacher=Person::where(DB::raw('concat(firstname," ",surname)') , '=' , $fullname)->first();
                        if ($preacher){
                            $set('person_id',$preacher->id);  
                        }
                    })
                    ->required(),
                Forms\Components\TextInput::make('video')
                    ->suffixAction(MediaAction::make('showVideo')
                        ->icon('heroicon-m-video-camera')
                        ->media(fn (Get $get) => $get('video'))
                ),
                Forms\Components\TextInput::make('audio')
                    ->suffixAction(MediaAction::make('playAudio')
                        ->icon('heroicon-m-musical-note')
                        ->media(fn (Get $get) => $get('audio'))
                ),
                Forms\Components\Select::make('person_id')
                    ->label('Preacher')
                    ->relationship(
                        name: 'person',
                        modifyQueryUsing: fn (Builder $query) => $query->orderBy('firstname')->orderBy('surname'),
                    )
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->firstname} {$record->surname}")
                    ->required()
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
                SpatieTagsInput::make('tags'),
                Forms\Components\Toggle::make('published'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('person.fullname')
                    ->label('Preacher')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('service.servicedate')
                    ->label('Date')
                    ->date('Y-m-d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('service.servicetime')
                    ->label('Time')
                    ->sortable(),
                Tables\Columns\TextColumn::make('series.series')
                    ->numeric()
                    ->sortable(),
            ])
            ->defaultSort('service.servicedate','DESC')
            ->filters([
                //
            ])
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSermons::route('/'),
            'create' => Pages\CreateSermon::route('/create'),
            'edit' => Pages\EditSermon::route('/{record}/edit'),
        ];
    }

}
