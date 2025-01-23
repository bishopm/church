<?php

namespace Bishopm\Church\Filament\Clusters\Website\Resources;

use Bishopm\Church\Filament\Clusters\Website;
use Bishopm\Church\Filament\Clusters\Website\Resources\SeriesResource\Pages;
use Bishopm\Church\Filament\Clusters\Website\Resources\SeriesResource\RelationManagers;
use Bishopm\Church\Models\Series;
use Filament\Forms;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class SeriesResource extends Resource
{
    protected static ?int $navigationSort = 3;

    protected static ?string $model = Series::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $cluster = Website::class;

    protected static ?string $modelLabel = 'Sermon series';

    public $record;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('series')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('startingdate')
                    ->native(false)
                    ->displayFormat('Y-m-d')
                    ->format('Y-m-d')
                    ->required(),
                Forms\Components\FileUpload::make('image')
                    ->directory('images/sermon')
                    ->previewable(false)
                    ->image()
                    ->required(),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('series')
                    ->searchable(),
                Tables\Columns\TextColumn::make('startingdate')
                    ->date('Y-m-d')
                    ->label('Starting date')
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image')
                    ->state(function (Series $record) {
                        return url('storage/' . $record->image);
                }),
            ])
            ->filters([
                //
            ])
            ->defaultSort('startingdate', 'desc')
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
            RelationManagers\SermonsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSeries::route('/'),
            'create' => Pages\CreateSeries::route('/create'),
            'edit' => Pages\EditSeries::route('/{record}/edit'),
        ];
    }
}
