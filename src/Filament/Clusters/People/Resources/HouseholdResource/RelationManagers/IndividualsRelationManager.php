<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources\HouseholdResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IndividualsRelationManager extends RelationManager
{
    protected static string $relationship = 'individuals';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Radio::make('title')
                    ->options([
                        'Dr' => 'Dr',
                        'Mr' => 'Mr',
                        'Mrs' => 'Mrs',
                        'Ms' => 'Ms',
                    ])                    ->inline(),
                Forms\Components\Radio::make('sex')
                    ->options([
                        'female' => 'Female',
                        'male' => 'Male',
                    ])
                    ->inline(),
                Forms\Components\TextInput::make('surname')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('firstname')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('memberstatus')
                    ->required()
                    ->options([
                        'member' => 'Member',
                        'child' => 'Child',
                        'non-member' => 'Non-member',
                    ])
                    ->default('member'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('cellphone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image')
                    ->image(),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('officephone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('giving')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('birthdate')
                    ->maxDate(now())
                    ->format('Y-m-d')
                    ->displayFormat('Y-m-d'),
                Forms\Components\TextInput::make('user_id')
                    ->numeric(),
                Forms\Components\TextInput::make('uid')
                    ->maxLength(255),

                Forms\Components\Checkbox::make('welcome_email'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('firstname')
            ->columns([
                Tables\Columns\TextColumn::make('firstname')->label('First name'),
                Tables\Columns\TextColumn::make('surname'),
                Tables\Columns\TextColumn::make('cellphone'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
