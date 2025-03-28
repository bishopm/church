<?php

namespace Bishopm\Church\Filament\Clusters\Reports\Resources;

use Bishopm\Church\Filament\Clusters\Reports;
use Bishopm\Church\Filament\Clusters\Reports\Resources\ReportResource\Pages;
use Bishopm\Church\Models\Report;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $cluster = Reports::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('url')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('category')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('parameter')
                    ->maxLength(199),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('url')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category')
                    ->searchable(),
                Tables\Columns\TextColumn::make('parameter')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('View')
                ->form(function (Report $record){
                    if ($record->parameter=="year"){
                        return [
                            Select::make('year')
                                ->label('Year')
                                ->options([
                                    date('Y',strtotime('-1 year'))=>date('Y',strtotime('-1 year')),
                                    date('Y')=>date('Y'),
                                    date('Y',strtotime('+1 year'))=>date('Y',strtotime('+1 year'))
                                ])
                                ->default(date('Y'))
                                ->required(),
                        ];
                    }
                })
                ->action(function (Report $record, array $data){
                    if (isset($data['year'])){
                        return redirect(url('/') . '/' . $record->url . '/' . $data['year']);
                    } else {
                        return redirect(url('/') . '/' . $record->url);
                    }
                })->openUrlInNewTab(),
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
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReport::route('/create'),
            'edit' => Pages\EditReport::route('/{record}/edit'),
        ];
    }
}
