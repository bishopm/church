<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources;

use Bishopm\Church\Filament\Clusters\Admin;
use Bishopm\Church\Filament\Clusters\Admin\Resources\MeetingResource\Pages;
use Bishopm\Church\Filament\Clusters\Admin\Resources\MeetingResource\RelationManagers;
use Bishopm\Church\Models\Group;
use Bishopm\Church\Models\Meeting;
use Filament\Forms;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Table;

class MeetingResource extends Resource
{
    protected static ?int $navigationSort = 1;

    protected static ?string $model = Meeting::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

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
                    ->native(true)
                    ->displayFormat('Y-m-d H:i')
                    ->format('Y-m-d H:i')
                    ->required(),
                TimePicker::make('endtime')
                    ->label('End time')
                    ->default(function() {
                        return date('H:00',strtotime('+3 hours'));
                    })
                    ->native(true)
                    ->seconds(false)
                    ->required(),
                Forms\Components\Select::make('venue_id')
                    ->relationship('venue', 'venue')
                    ->required(),
                Forms\Components\Select::make('group_id')
                    ->relationship('group', 'groupname')
                    ->required(),
                Forms\Components\Select::make('attendance')
                    ->hiddenOn('create')
                    ->multiple()
                    ->options(function (Get $get){
                        if ($get('group_id')){
                            $group=Group::with('individuals')->where('id',$get('group_id'))->first();
                            foreach ($group->individuals as $indiv){
                                $data[$indiv->id]=$indiv->firstname . " " . $indiv->surname;
                            }
                            asort($data);
                            return $data;
                        }
                    })
                    ->searchable(),
                Forms\Components\Checkbox::make('calendar')->label('Add to church calendar'),
                Forms\Components\DateTimePicker::make('nextmeeting')
                    ->label('Next meeting')
                    ->default(now())
                    ->native(true)
                    ->displayFormat('Y-m-d H:i')
                    ->format('Y-m-d H:i'),
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
                Tables\Columns\IconColumn::make('calendar')
                    ->boolean(),
            ])
            ->defaultSort('meetingdatetime','ASC')
            ->filters([
                Filter::make('hide_older_meetings')
                ->query(fn (Builder $query): Builder => $query->where('meetingdatetime', '>', date('Y-m-d H:i:00',strtotime('14 days ago'))))
                ->default()
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
