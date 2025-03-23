<?php

namespace Bishopm\Church\Filament\Clusters\Property\Resources;

use Bishopm\Church\Filament\Clusters\Property;
use Bishopm\Church\Filament\Clusters\Property\Resources\TenantResource\Pages;
use Bishopm\Church\Filament\Clusters\Property\Resources\TenantResource\RelationManagers;
use Bishopm\Church\Models\Tenant;
use Filament\Forms;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $cluster = Property::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $modelLabel = 'Venue user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('tenant')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('contact')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image')
                    ->image(),
                Forms\Components\Textarea::make('description'),
                SpatieTagsInput::make('tags')->type('tenants'),
                Forms\Components\TextInput::make('slug')
                    ->required(),
                Forms\Components\Toggle::make('active'),
                Forms\Components\Toggle::make('publish')->label('Publish on Hub website')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tenant')->label('Group')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tags.name')
                    ->badge()
                    ->forceSearchCaseInsensitive(true)
                    ->searchable(),
                
            ])
            ->filters([
                //
            ])
            ->defaultSort('tenant','ASC')
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
            RelationManagers\DiaryentriesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'edit' => Pages\EditTenant::route('/{record}/edit'),
        ];
    }
}
