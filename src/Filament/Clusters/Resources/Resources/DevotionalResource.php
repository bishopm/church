<?php

namespace Bishopm\Church\Filament\Clusters\Resources\Resources;

use Bishopm\Church\Filament\Clusters\Resources;
use Bishopm\Church\Filament\Clusters\Resources\Resources\DevotionalResource\Pages;
use Bishopm\Church\Filament\Clusters\Resources\Resources\DevotionalResource\RelationManagers;
use Bishopm\Church\Models\Devotional;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DevotionalResource extends Resource
{
    protected static ?string $model = Devotional::class;

    protected static ?string $navigationIcon = 'heroicon-o-plus-circle';

    protected static ?string $cluster = Resources::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('image')
                    ->image(),
                Forms\Components\DatePicker::make('publicationdate')
                    ->required(),
                Forms\Components\TextInput::make('reading')
                    ->required()
                    ->maxLength(199),
                Forms\Components\TextInput::make('version')
                    ->required()
                    ->maxLength(25),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('publicationdate')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('reading')
                    ->searchable(),
                Tables\Columns\TextColumn::make('version')
                    ->searchable(),
            ])
            ->defaultSort('publicationdate','DESC')
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
            'index' => Pages\ListDevotionals::route('/'),
            'create' => Pages\CreateDevotional::route('/create'),
            'edit' => Pages\EditDevotional::route('/{record}/edit'),
        ];
    }
}
