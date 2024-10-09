<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources;

use Bishopm\Church\Filament\Clusters\Admin;
use Bishopm\Church\Filament\Clusters\Admin\Resources\MeetingResource\Pages;
use Bishopm\Church\Filament\Clusters\Admin\Resources\MeetingResource\RelationManagers;
use Bishopm\Church\Models\Meeting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MeetingResource extends Resource
{
    protected static ?int $navigationSort = 1;

    protected static ?string $model = Meeting::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Admin::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('details')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('meetingdatetime')
                    ->label('Date and time')
                    ->default(now())
                    ->native(false)
                    ->displayFormat('Y-m-d H:i')
                    ->format('Y-m-d H:i')
                    ->required(),
                Forms\Components\Select::make('venue_id')
                    ->relationship('venue', 'venue')
                    ->required(),
                Forms\Components\Select::make('agenda')
                    ->visibleOn('create')
                    ->dehydrated(false)
                    ->options(function () {
                        $data=array();
                        if (setting('admin.agendas')){
                            foreach (setting('admin.agendas') as $key=>$val){
                                $data[$val]=$key;
                            }
                        }
                        return $data;
                    })
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('meetingdatetime')
                    ->label('Date and time')
                    ->date('Y-m-d H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('details')
                    ->searchable(),
                Tables\Columns\TextColumn::make('venue.venue')
                    ->numeric()
                    ->sortable(),
            ])
            ->defaultSort('meetingdatetime','DESC')
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
            RelationManagers\AgendaitemsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMeetings::route('/'),
            'create' => Pages\CreateMeeting::route('/create'),
            'edit' => Pages\EditMeeting::route('/{record}/edit'),
        ];
    }
}
