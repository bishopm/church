<?php

namespace Bishopm\Church\Filament\Clusters\Worship\Resources;

use Bishopm\Church\Filament\Clusters\Worship;
use Bishopm\Church\Filament\Clusters\Worship\Resources\ChordResource\Pages;
use Bishopm\Church\Filament\Clusters\Worship\Resources\ChordResource\RelationManagers;
use Bishopm\Church\Models\Chord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChordResource extends Resource
{
    protected static ?string $model = Chord::class;

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-musical-note';

    protected static ?string $cluster = Worship::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('chord')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('s1')->label('E string (treble)')
                    ->maxLength(10),
                Forms\Components\TextInput::make('s2')->label('B string')
                    ->maxLength(10),
                Forms\Components\TextInput::make('s3')->label('G string')
                    ->maxLength(10),
                Forms\Components\TextInput::make('s4')->label('D string')
                    ->maxLength(10),
                Forms\Components\TextInput::make('s5')->label('A string')
                    ->maxLength(10),
                Forms\Components\TextInput::make('s6')->label('E string (bass)')
                    ->maxLength(10),
                Forms\Components\TextInput::make('fret')
                    ->maxLength(10),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('chord')
                    ->searchable(),
                Tables\Columns\TextColumn::make('s1')->label('E')
                    ->searchable(),
                Tables\Columns\TextColumn::make('s2')->label('B')
                    ->searchable(),
                Tables\Columns\TextColumn::make('s3')->label('G')
                    ->searchable(),
                Tables\Columns\TextColumn::make('s4')->label('D')
                    ->searchable(),
                Tables\Columns\TextColumn::make('s5')->label('A')
                    ->searchable(),
                Tables\Columns\TextColumn::make('s6')->label('E')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fret')
                    ->searchable(),
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
            'index' => Pages\ListChords::route('/'),
            'create' => Pages\CreateChord::route('/create'),
            'edit' => Pages\EditChord::route('/{record}/edit'),
        ];
    }
}
