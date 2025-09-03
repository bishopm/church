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
use Joaopaulolndev\FilamentPdfViewer\Forms\Components\PdfViewerField;

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
                Forms\Components\Group::make([
                    Forms\Components\TextInput::make('chord')
                        ->required()
                        ->maxLength(50),
                    Forms\Components\Select::make('fret')
                        ->required()
                        ->default(0)
                        ->options([0,1,2,3,4,5,6,7,8,9,10]),
                    PdfViewerField::make('preview')
                        ->hiddenOn('create')
                        ->label('')
                        ->minHeight('30svh')
                        ->fileUrl(fn (Chord $record) => url('/') . '/admin/reports/chord/' . $record->id),
                ]),
                Forms\Components\Group::make([
                    Forms\Components\Select::make('s1')->label('E string (treble)')
                        ->required()
                        ->options([0,1,2,3,4,5,6,7,8,9,10,11,'x'=>'x']),
                    Forms\Components\Select::make('s2')->label('B string')
                        ->required()
                        ->options([0,1,2,3,4,5,6,7,8,9,10,11,'x'=>'x']),
                    Forms\Components\Select::make('s3')->label('G string')
                        ->required()
                        ->options([0,1,2,3,4,5,6,7,8,9,10,11,'x'=>'x']),
                    Forms\Components\Select::make('s4')->label('D string')
                        ->required()
                        ->options([0,1,2,3,4,5,6,7,8,9,10,11,'x'=>'x']),
                    Forms\Components\Select::make('s5')->label('A string')
                        ->required()
                        ->options([0,1,2,3,4,5,6,7,8,9,10,11,'x'=>'x']),
                    Forms\Components\Select::make('s6')->label('E string (bass)')
                        ->required()
                        ->options([0,1,2,3,4,5,6,7,8,9,10,11,'x'=>'x']),
                ])
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
