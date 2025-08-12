<?php

namespace Bishopm\Church\Filament\Clusters\Property\Resources;

use Bishopm\Church\Filament\Clusters\Property;
use Bishopm\Church\Filament\Clusters\Property\Resources\VenueResource\Pages;
use Bishopm\Church\Models\Venue;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class VenueResource extends Resource
{
    protected static ?int $navigationSort = 1;

    protected static ?string $model = Venue::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    protected static ?string $cluster = Property::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('venue')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image')
                    ->directory('images/venue')
                    ->image(),
                Forms\Components\Textarea::make('description'),
                Forms\Components\TextInput::make('slug')
                    ->required(),
                Forms\Components\TextInput::make('width')
                    ->numeric(),
                Forms\Components\TextInput::make('length')
                    ->numeric(),
                Forms\Components\Select::make('tags')->label('Features')
                    ->relationship('tags','name',modifyQueryUsing: fn (Builder $query) => $query->where('type','venue'))
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
                                    ->default('venue')
                                    ->readonly()
                                    ->required(),
                                Forms\Components\TextInput::make('slug')
                                    ->required(),
                            ])
                    ]),
                Forms\Components\Toggle::make('publish')->label('Publish on Hub website'),
                Forms\Components\Toggle::make('resource')
                    ->hiddenOn('view')
                    ->label('Show in resources view'),
                Forms\Components\ColorPicker::make('colour')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('venue')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\ColorColumn::make('colour')
            ])
            ->defaultSort('venue','ASC')
            ->filters([
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListVenues::route('/'),
            'create' => Pages\CreateVenue::route('/create'),
            'view' => Pages\ViewVenue::route('/{record}'),
            'edit' => Pages\EditVenue::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array {
        return [
            \Bishopm\Church\Filament\Widgets\ChurchCalendarWidget::class,
            \Bishopm\Church\Filament\Widgets\ChurchVenuesWidget::class,
        ];
    }
}
