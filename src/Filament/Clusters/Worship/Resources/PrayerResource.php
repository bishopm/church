<?php

namespace Bishopm\Church\Filament\Clusters\Worship\Resources;

use Bishopm\Church\Filament\Clusters\Worship;
use Bishopm\Church\Filament\Clusters\Worship\Resources\PrayerResource\Pages;
use Bishopm\Church\Models\Prayer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class PrayerResource extends Resource
{
    protected static ?string $model = Prayer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $cluster = Worship::class;

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'liturgy';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('copyright')
                    ->maxLength(191),
                Forms\Components\RichEditor::make('words')
                    ->required(),
                Forms\Components\Placeholder::make('openlp')->label('OpenLP')
                    ->content(function (Get $get){
                        $lyrics = $get('words');
                        $lyrics=str_replace("&nbsp;",' ',$lyrics);
                        preg_match_all('/<strong>(.*?)<\/strong>/s', $lyrics, $bolds);
                        foreach ($bolds[0] as $bold){
                            $lyrics=str_replace($bold,strtoupper($bold),$lyrics);
                        }
                        $lyrics=str_replace("</p>","<br>",$lyrics);
                        $lyrics=str_replace("<p>","<br>",$lyrics);
                        $lyrics=str_replace("\\n","<br>",$lyrics);
                        $lyrics=str_replace("\t", '', $lyrics);
                        return new HtmlString($lyrics);
                    }),
                    Forms\Components\Select::make('tags')
                        ->relationship('tags','name')
                        ->multiple(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(['title','words'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('tags.name')
                    ->badge()
                    ->forceSearchCaseInsensitive(true)
                    ->searchable()
            ])
            ->defaultSort('title','ASC')
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPrayers::route('/'),
            'create' => Pages\CreatePrayer::route('/create'),
            'edit' => Pages\EditPrayer::route('/{record}/edit'),
        ];
    }
}
