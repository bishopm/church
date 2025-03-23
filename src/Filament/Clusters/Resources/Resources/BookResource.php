<?php

namespace Bishopm\Church\Filament\Clusters\Resources\Resources;

use Bishopm\Church\Filament\Clusters\Resources;
use Bishopm\Church\Filament\Clusters\Resources\Resources\BookResource\Pages;
use Bishopm\Church\Filament\Clusters\Resources\Resources\BookResource\RelationManagers;
use Bishopm\Church\Models\Book;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Http;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $cluster = Resources::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('isbn')
                    ->label('ISBN')
                    ->live(onBlur: true)
                    ->maxLength(199)
                    ->afterStateUpdated(function ($state, Set $set){
                        if ($state){
                            $url="https://www.googleapis.com/books/v1/volumes?key=" . setting('services.google_api'). "&q=isbn:" . $state;
                            $response=Http::get($url);
                            $book=json_decode($response->body())->items[0]->volumeInfo;
                            if (isset($book->subtitle)){
                                $set('title', $book->title . ": " . $book->subtitle);
                            } else {
                                $set('title', $book->title);
                            }
                            if (isset($book->publisher)){
                                $set('publisher', $book->publisher);
                            }
                            if (isset($book->description)){
                                $set('description', $book->description);
                            }
                            $set('image', $book->imageLinks->thumbnail);
                            $aarray=array();
                            foreach ($book->authors as $author){
                                $aarray[]=['name'=>$author];
                            }
                            $set('authors',$aarray);
                        }
                    }),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('image')
                    ->maxLength(199),
                Forms\Components\TextInput::make('publisher')
                    ->maxLength(199),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                TagsInput::make('tags'),
                Forms\Components\Repeater::make('authors')
                    ->required()
                    ->schema([
                        Forms\Components\TextInput::make('name')->required()
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('allauthors')
                    ->label('Author')
                    ->badge()
                    ->separator(','),
                Tables\Columns\ImageColumn::make('image'),
            ])
            ->defaultSort('title', 'asc')
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
            RelationManagers\LoansRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
