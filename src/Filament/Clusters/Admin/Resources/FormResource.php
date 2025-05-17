<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources;

use Bishopm\Church\Filament\Clusters\Admin;
use Bishopm\Church\Filament\Clusters\Admin\Resources\FormResource\Pages;
use Bishopm\Church\Filament\Clusters\Admin\Resources\FormResource\RelationManagers;
use Bishopm\Church\Models\Form as FormModel;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Joaopaulolndev\FilamentPdfViewer\Forms\Components\PdfViewerField;

class FormResource extends Resource
{
    protected static ?string $model = FormModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Admin::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('orientation')
                            ->selectablePlaceholder(false)
                            ->default('portrait')
                            ->options([
                                'portrait'=>'Portrait',
                                'landscape'=>'Landscape'
                            ]),
                        Forms\Components\Select::make('width')
                            ->selectablePlaceholder(false)
                            ->default('full')
                            ->options([
                                'full'=>'100%',
                                'half'=>'50%',
                                'third'=>'33%',
                                'quarter'=>'25%'
                            ]),
                        Forms\Components\Select::make('font')
                            ->label('Default font')
                            ->required()
                            ->selectablePlaceholder(false)
                            ->default('Arial')
                            ->options([
                                'Arial'=>'Arial',
                                'Courier'=>'Courier',
                                'Times'=>'Times New Roman'
                            ]),
                        Forms\Components\TextInput::make('fontsize')
                            ->label('Default font size')
                            ->default(12)
                            ->numeric()
                            ->required(),
                    ]),
                Group::make()
                    ->schema([
                        PdfViewerField::make('pdf_preview')
                            ->hiddenOn('create')
                            ->label('')
                            ->minHeight('50svh')
                            ->fileUrl(fn ($livewire) => $livewire->pdfUrl)
                            ->columnSpanFull()
                            ->live(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('orientation')
                    ->searchable(),
                Tables\Columns\TextColumn::make('width')
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
            RelationManagers\FormitemsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListForms::route('/'),
            'create' => Pages\CreateForm::route('/create'),
            'edit' => Pages\EditForm::route('/{record}/edit'),
        ];
    }
}
