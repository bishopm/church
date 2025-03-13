<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources;

use Bishopm\Church\Filament\Clusters\Admin;
use Bishopm\Church\Filament\Clusters\Admin\Resources\StatisticResource\Pages;
use Bishopm\Church\Filament\Clusters\Admin\Resources\StatisticResource\RelationManagers;
use Bishopm\Church\Models\Statistic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StatisticResource extends Resource
{    
    protected static ?int $navigationSort = 3;
    
    protected static ?string $model = Statistic::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $cluster = Admin::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('statdate')
                    ->native(false)
                    ->displayFormat('Y-m-d')
                    ->format('Y-m-d')
                    ->required(),
                Forms\Components\Select::make('servicetime')
                    ->required()
                    ->label('Service time')
                    ->options(fn () => array_combine(setting('general.services'),setting('general.services')))
                    ->placeholder(''),
                Forms\Components\TextInput::make('attendance')
                    ->required()
                    ->numeric(),
                Forms\Components\Toggle::make('included')->label('Include in average')
                    ->default(1)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('statdate')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('servicetime')
                    ->searchable(),
                Tables\Columns\TextColumn::make('attendance')
                    ->numeric()
                    ->sortable(),
            ])
            ->defaultSort(fn ($query) => $query->orderBy('statdate', 'desc')->orderBy('servicetime', 'asc'))
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStatistics::route('/'),
            'create' => Pages\CreateStatistic::route('/create'),
            'edit' => Pages\EditStatistic::route('/{record}/edit'),
        ];
    }
}
