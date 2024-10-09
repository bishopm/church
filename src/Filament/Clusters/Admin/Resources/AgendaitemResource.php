<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources;

use Bishopm\Church\Filament\Clusters\Admin;
use Bishopm\Church\Filament\Clusters\Admin\Resources\AgendaitemResource\Pages;
use Bishopm\Church\Filament\Clusters\Admin\Resources\AgendaitemResource\RelationManagers;
use Bishopm\Church\Models\Agendaitem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AgendaitemResource extends Resource
{
    protected static ?string $model = Agendaitem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Admin::class;

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('meeting_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('heading')
                    ->required()
                    ->maxLength(199),
                Forms\Components\TextInput::make('sortorder')
                    ->default(0)
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('level')
                    ->default(1)
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('meeting_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('heading')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sortorder')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('level')
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
            'index' => Pages\ListAgendaitems::route('/'),
            'create' => Pages\CreateAgendaitem::route('/create'),
            'edit' => Pages\EditAgendaitem::route('/{record}/edit'),
        ];
    }
}
