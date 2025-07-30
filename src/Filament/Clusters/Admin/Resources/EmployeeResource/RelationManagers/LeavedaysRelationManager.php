<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources\EmployeeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeavedaysRelationManager extends RelationManager
{
    protected static string $relationship = 'leavedays';

    protected static ?string $modelLabel = 'Leave day';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('startdate')
                    ->label('Start date')
                    ->native(false)
                    ->displayFormat('Y-m-d')
                    ->format('Y-m-d')
                    ->default(date('Y-m-d'))
                    ->required(),
                Forms\Components\DatePicker::make('enddate')
                    ->label('End date')
                    ->native(false)
                    ->displayFormat('Y-m-d')
                    ->format('Y-m-d')
                    ->default(date('Y-m-d'))
                    ->required(),
                Forms\Components\TextInput::make('numberofdays')
                    ->label('Number of days')
                    ->required()
                    ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('startdate')
            ->columns([
                Tables\Columns\TextColumn::make('startdate')->label('Start date'),
                Tables\Columns\TextColumn::make('enddate')->label('End date'),
                Tables\Columns\TextColumn::make('numberofdays')->label('Days')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
