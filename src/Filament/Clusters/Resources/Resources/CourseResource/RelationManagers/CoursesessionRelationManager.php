<?php

namespace Bishopm\Church\Filament\Clusters\Resources\Resources\CourseResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Hugomyb\FilamentMediaAction\Forms\Components\Actions\MediaAction;

class CoursesessionRelationManager extends RelationManager
{
    protected static string $relationship = 'coursesessions';

    protected static ?string $title = 'Sessions';

    protected static ?string $modelLabel = 'session';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('session')->label('Session name')
                    ->columnSpanFull()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('video')
                    ->suffixAction(MediaAction::make('showVideo')
                        ->icon('heroicon-m-video-camera')),
                Forms\Components\FileUpload::make('file')
                    ->directory('course'),
                Forms\Components\RichEditor::make('notes')
                    ->columnSpanFull()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('session')
            ->columns([
                Tables\Columns\TextColumn::make('session'),
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
