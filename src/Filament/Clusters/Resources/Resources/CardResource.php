<?php

namespace Bishopm\Church\Filament\Clusters\Resources\Resources;

use Bishopm\Church\Filament\Clusters\Resources;
use Bishopm\Church\Filament\Clusters\Resources\Resources\CardResource\Pages;
use Bishopm\Church\Filament\Clusters\Resources\Resources\CardResource\RelationManagers;
use Bishopm\Church\Filament\Clusters\Resources\Resources\CardsResource\RelationManagers\CarditemsRelationManager;
use Bishopm\Church\Models\Card;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Mansoor\UnsplashPicker\Actions\UnsplashPickerAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CardResource extends Resource
{
    protected static ?string $model = Card::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Resources::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('card')
                    ->required()
                    ->maxLength(191),
                Forms\Components\FileUpload::make('image')
                        ->hintAction(
                            UnsplashPickerAction::make()
                                ->useSquareDisplay(false)
                        )
                        ->image()
                        ->directory('images/blog')
                        ->previewable(false)
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('16:9')
                        ->imageResizeTargetWidth('960')
                        ->imageResizeTargetHeight('540'),
                Forms\Components\TextInput::make('url')
                    ->maxLength(191),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('card')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('url')
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
            CarditemsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCards::route('/'),
            'create' => Pages\CreateCard::route('/create'),
            'edit' => Pages\EditCard::route('/{record}/edit'),
        ];
    }
}
