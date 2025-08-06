<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources;

use Bishopm\Church\Filament\Clusters\Admin;
use Bishopm\Church\Filament\Clusters\Admin\Resources\MeetingtaskResource\Pages;
use Bishopm\Church\Filament\Clusters\Admin\Resources\MeetingtaskResource\RelationManagers;
use Bishopm\Church\Models\Agendaitem;
use Bishopm\Church\Models\Individual;
use Bishopm\Church\Models\Meetingtask;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MeetingtaskResource extends Resource
{
    protected static ?string $model = Meetingtask::class;

    protected static ?int $navigationSort = 6;

    protected static ?string $modelLabel = 'Meeting task';

    protected static ?string $navigationIcon = 'heroicon-o-check';

    protected static ?string $cluster = Admin::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('individual_id')->label('Individual')
                    ->options(Individual::orderBy('firstname')->get()->pluck('fullname', 'id'))
                    ->required(),
                Forms\Components\Select::make('agendaitem_id')->label('Agenda item')
                    ->options(Agendaitem::orderBy('heading')->get()->pluck('heading', 'id')),
                Forms\Components\DatePicker::make('duedate')->label('Due date'),
                Forms\Components\TextInput::make('visibility')
                    ->required()
                    ->maxLength(199)
                    ->default('public'),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(199)
                    ->default('todo'),
                Forms\Components\TextInput::make('statusnote')
                    ->label('Status note')
                    ->maxLength(199),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('individual.fullname')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('duedate')->label('Due date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('visibility')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('statusnote')->label('Status note')
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
            'index' => Pages\ListMeetingtasks::route('/'),
            'create' => Pages\CreateMeetingtask::route('/create'),
            'edit' => Pages\EditMeetingtask::route('/{record}/edit'),
        ];
    }
}
