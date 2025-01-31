<?php

namespace Bishopm\Church\Filament\Clusters\Worship\Resources;

use Bishopm\Church\Filament\Clusters\Worship;
use Bishopm\Church\Filament\Clusters\Worship\Resources\PrayerResource\Pages;
use Bishopm\Church\Models\Prayer;
use Filament\Forms;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieTagsColumn;
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
                SpatieTagsInput::make('tags')
                    ->type('prayer'),
                Forms\Components\RichEditor::make('words')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Placeholder::make('openlp')->label('OpenLP')
                    ->content(function (Get $get){
                        $htmlContent = $get('words');
                        preg_match_all('/<strong>(.*?)<\/strong>/s', $htmlContent, $matches);
                        foreach ($matches[1] as $thisone){
                            $htmlContent=str_replace($thisone,strtoupper($thisone),$htmlContent);
                        }
                        $htmlContent=str_replace('<strong>','',$htmlContent);
                        $htmlContent=str_replace('</strong>','',$htmlContent);
                        return new HtmlString($htmlContent);
                    })
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
