<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources;

use Bishopm\Church\Filament\Clusters\Admin;
use Bishopm\Church\Filament\Clusters\Admin\Resources\RecurringtaskResource\Pages;
use Bishopm\Church\Filament\Clusters\Admin\Resources\RecurringtaskResource\RelationManagers;
use Bishopm\Church\Models\Individual;
use Bishopm\Church\Models\Recurringtask;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RecurringtaskResource extends Resource
{
    protected static ?string $model = Recurringtask::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $cluster = Admin::class;

    protected static ?string $modelLabel = 'Recurring task';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('frequency')
                    ->options([
                        'weekly'=>'Weekly',
                        'monthly'=>'Monthly'
                    ])
                    ->required()
                    ->default('weekly'),
                Forms\Components\Select::make('taskday')
                    ->options([
                        '1'=>'Monday',
                        '2'=>'Tuesday',
                        '3'=>'Wednesday',
                        '4'=>'Thursday',
                        '5'=>'Friday',
                        '6'=>'Saturday',
                        '7'=>'Sunday'
                    ])
                    ->default('2')
                    ->required(),
                Forms\Components\Select::make('individual_id')
                    ->label('Assigned to')
                    ->options(Individual::orderBy('firstname')->get()->pluck('fullname', 'id'))
                    ->searchable(),
                Forms\Components\Select::make('visibility')
                    ->options([
                        'private'=>'Private',
                        'public'=>'Public'
                    ])
                    ->required()
                    ->default('public'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('frequency')
                    ->searchable(),
                Tables\Columns\SelectColumn::make('taskday')
                    ->options([
                        '1'=>'Monday',
                        '2'=>'Tuesday',
                        '3'=>'Wednesday',
                        '4'=>'Thursday',
                        '5'=>'Friday',
                        '6'=>'Saturday',
                        '7'=>'Sunday'
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('individual.fullname')
                    ->numeric()
                    ->sortable(),
            ])
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
            'index' => Pages\ListRecurringtasks::route('/'),
            'create' => Pages\CreateRecurringtask::route('/create'),
            'edit' => Pages\EditRecurringtask::route('/{record}/edit'),
        ];
    }
}
