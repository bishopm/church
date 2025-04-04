<?php

namespace Bishopm\Church\Filament\Clusters\Resources\Resources;

use Bishopm\Church\Filament\Clusters\Resources;
use Bishopm\Church\Filament\Clusters\Resources\Resources\CourseResource\Pages;
use Bishopm\Church\Filament\Clusters\Resources\Resources\CourseResource\RelationManagers;
use Bishopm\Church\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $cluster = Resources::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('course')
                    ->required()
                    ->maxLength(199),
                Forms\Components\Select::make('venue_id')
                    ->label('Venue')
                    ->relationship('venue', 'venue'),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('image')
                    ->directory('images/course')
                    ->image(),
                Forms\Components\FileUpload::make('leadernotes')->label('Leader notes')
                    ->directory('course')
                    ->disk('google'),
                Forms\Components\Select::make('tags')
                    ->relationship('tags','name',modifyQueryUsing: fn (Builder $query) => $query->where('type','course'))
                    ->multiple()
                    ->createOptionForm([
                        Forms\Components\Grid::make()
                            ->columns(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                    ->required(),
                                Forms\Components\TextInput::make('type')
                                    ->default('course')
                                    ->readonly()
                                    ->required(),
                                Forms\Components\TextInput::make('slug')
                                    ->required(),
                            ])
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('course')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tags.name')->label('Subject')
                    ->badge()
                    ->forceSearchCaseInsensitive(true)
                    ->searchable()
            ])
            ->defaultSort('course','DESC')
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
            RelationManagers\CoursesessionRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
