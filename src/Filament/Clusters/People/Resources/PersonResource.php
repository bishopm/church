<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources;

use Bishopm\Church\Filament\Clusters\People;
use Bishopm\Church\Filament\Clusters\People\Resources\PersonResource\Pages;
use Bishopm\Church\Models\Person;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PersonResource extends Resource
{
    protected static ?int $navigationSort = 5;

    protected static ?string $model = Person::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $cluster = People::class;

    protected static ?string $modelLabel = 'Staff / Preacher';

    public static function form(Form $form): Form

    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('firstname')
                    ->label('First name')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, Get $get, ?string $state) => $set('slug', Str::slug($state . " " . $get('surname'))))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('surname')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, Get $get, ?string $state) => $set('slug', Str::slug($get('firstname') . " " . $state)))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('bio')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('image')
                    ->directory('images/people')
                    ->previewable(false)
                    ->image(),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TagsInput::make('role')
                    ->suggestions([
                        'Blogger',
                        'Preacher',
                        'Staff'
                    ])
                    ->required(),
                Forms\Components\Toggle::make('active'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('firstname')
                    ->searchable(),
                Tables\Columns\TextColumn::make('surname')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
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
            'index' => Pages\ListPeople::route('/'),
            'create' => Pages\CreatePerson::route('/create'),
            'edit' => Pages\EditPerson::route('/{record}/edit'),
        ];
    }
}
