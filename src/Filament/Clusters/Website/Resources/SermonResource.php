<?php

namespace Bishopm\Church\Filament\Clusters\Website\Resources;

use Bishopm\Church\Filament\Clusters\Website;
use Bishopm\Church\Filament\Clusters\Website\Resources\SermonResource\Pages;
use Bishopm\Church\Filament\Clusters\Website\Resources\SermonResource\RelationManagers;
use Bishopm\Church\Models\Sermon;
use Bishopm\Church\Models\Series;
use Filament\Forms;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Hugomyb\FilamentMediaAction\Forms\Components\Actions\MediaAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;

class SermonResource extends Resource
{
    protected static ?int $navigationSort = 2;
    
    protected static ?string $model = Sermon::class;

    protected static ?string $navigationIcon = 'heroicon-o-microphone';

    protected static ?string $cluster = Website::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
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
                Forms\Components\DatePicker::make('servicedate')
                    ->label('Service date')
                    ->native(false)
                    ->displayFormat('Y-m-d')
                    ->format('Y-m-d')
                    ->required(),
                Forms\Components\TextInput::make('readings')
                    ->maxLength(255),
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
                SpatieTagsInput::make('tags'),
                Forms\Components\Select::make('series_id')
                    ->relationship(name: 'series', titleAttribute: 'series')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('series')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('startingdate')
                            ->required(),
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->required(),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->columnSpanFull(),
                    ]),                
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
                Tables\Columns\TextColumn::make('servicedate')
                    ->date('Y-m-d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tags.name')
                    ->badge()
                    ->forceSearchCaseInsensitive(true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('series.series')
                    ->numeric()
                    ->sortable(),
            ])
            ->defaultSort('servicedate','DESC')
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
