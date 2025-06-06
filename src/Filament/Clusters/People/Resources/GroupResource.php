<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources;

use Bishopm\Church\Filament\Clusters\People\Resources\GroupResource\Pages;
use Bishopm\Church\Filament\Clusters\People\Resources\GroupResource\RelationManagers;
use Bishopm\Church\Filament\Clusters\People;
use Bishopm\Church\Models\Group;
use Bishopm\Church\Models\Individual;
use Filament\Forms;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class GroupResource extends Resource
{
    protected static ?int $navigationSort = 2;

    protected static ?string $model = Group::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $cluster = People::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('groupname')
                    ->label('Group name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->maxLength(255),
                Forms\Components\Select::make('grouptype')
                    ->required()
                    ->options([
                        'admin' => 'Admin',
                        'fellowship' => 'Fellowship',
                        'service' => 'Service',
                    ])
                    ->default('service')
                    ->label('Group type'),
                Forms\Components\Textarea::make('description'),
                Forms\Components\FileUpload::make('image')
                    ->directory('images/groups')
                    ->image(),
                Forms\Components\Select::make('meetingday')
                    ->options([
                        '0' => 'Sunday',
                        '1' => 'Monday',
                        '2' => 'Tuesday',
                        '3' => 'Wednesday',
                        '4' => 'Thursday',
                        '5' => 'Friday',
                        '6' => 'Saturday'
                    ])
                    ->label('Meeting day'),
                TimePicker::make('meetingtime')
                    ->label('Meeting time')
                    ->native(true)
                    ->seconds(false), 
                Forms\Components\Select::make('individual_id')
                    ->label('Leader')
                    ->options(Individual::orderBy('firstname')->get()->pluck('fullname', 'id'))
                    ->searchable(),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('publish'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('groupname')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('individual.fullname')
                    ->label('Leader'),
                Tables\Columns\IconColumn::make('grouptype')->label('Type')
                    ->icon(fn (string $state): string => match ($state) {
                        'admin' => 'heroicon-o-chart-bar',
                        'fellowship' => 'heroicon-o-user-group',
                        'service' => 'heroicon-o-wrench-screwdriver',
                    }),
            ])
            ->filters([
                SelectFilter::make('grouptype')->label('')
                ->options([
                    'admin' => 'Admin',
                    'fellowship' => 'Fellowship',
                    'service' => 'Service'
                ])
            ])
            ->defaultSort('groupname', 'asc')
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
            RelationManagers\GroupmembersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGroups::route('/'),
            'create' => Pages\CreateGroup::route('/create'),
            'edit' => Pages\EditGroup::route('/{record}/edit'),
        ];
    }
}
