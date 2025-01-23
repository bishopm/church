<?php

namespace Bishopm\Church\Filament\Clusters\Property\Resources;

use Bishopm\Church\Filament\Clusters\Property;
use Bishopm\Church\Filament\Clusters\Property\Resources\CourseResource\Pages;
use Bishopm\Church\Filament\Clusters\Property\Resources\CourseResource\RelationManagers;
use Bishopm\Church\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourseResource extends Resource
{
    protected static ?int $navigationSort = 5;

    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Property::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('course')
                    ->required()
                    ->maxLength(199),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('coursedate')
                    ->label('Date and time')
                    ->default(now())
                    ->native(true)
                    ->displayFormat('Y-m-d H:i')
                    ->format('Y-m-d H:i')
                    ->required(),
                Forms\Components\Select::make('venue_id')
                    ->label('Venue')
                    ->relationship('venue', 'venue')
                    ->required(),
                Forms\Components\Checkbox::make('calendar')
                    ->label('Add to church calendar'),
                Forms\Components\FileUpload::make('image')
                    ->directory('images/course')
                    ->previewable(false)
                    ->image(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('course')
                    ->searchable(),
                Tables\Columns\TextColumn::make('coursedate')
                    ->searchable(),
                Tables\Columns\IconColumn::make('calendar')
                    ->boolean(),
            ])
            ->defaultSort('coursedate','DESC')
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
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
