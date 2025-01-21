<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources;

use Bishopm\Church\Filament\Clusters\People;
use Bishopm\Church\Filament\Clusters\People\Resources\PastoralcaseResource\Pages;
use Bishopm\Church\Models\Pastor;
use Bishopm\Church\Models\Pastoralcase;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PastoralcaseResource extends Resource
{
    protected static ?string $model = Pastoralcase::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = People::class;

    protected static ?string $modelLabel = 'Pastoral case';

    public $record;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('pastorable_id')->label('Name')
                    ->formatStateUsing(function (Pastoralcase $record){
                        if ($record->pastorable_type=="household"){
                            return $record->pastorable->addressee;
                        } else {
                            return $record->pastorable->fullname;
                        }
                    })
                    ->readonly()
                    ->required(),
                Forms\Components\TextInput::make('pastorable_type')
                    ->hidden()
                    ->required()
                    ->maxLength(199),
                Forms\Components\Select::make('pastor_id')
                    ->label('Pastoral carer')
                    ->options(Pastor::where('active',1)->get()->pluck('individual.fullname', 'id'))
                    ->searchable(),
                Forms\Components\TextInput::make('details')
                    ->maxLength(199),
                Forms\Components\Toggle::make('active')
                    ->required(),
                Forms\Components\Toggle::make('prayerlist'),
                Forms\Components\TextInput::make('prayernote')
                    ->maxLength(199),
                Forms\Components\TextInput::make('pastoralnotes')
                    ->formatStateUsing(function (Pastoralcase $record){
                        dd($record->pastorable->pastoralnotes);
                    })
                    ->maxLength(199),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pastor.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pastorable_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pastorable_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('details')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('active')
                    ->boolean(),
                Tables\Columns\IconColumn::make('prayerlist')
                    ->boolean(),
                Tables\Columns\TextColumn::make('prayernote')
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPastoralcases::route('/'),
            'create' => Pages\CreatePastoralcase::route('/create'),
            'edit' => Pages\EditPastoralcase::route('/{record}/edit'),
        ];
    }
}
