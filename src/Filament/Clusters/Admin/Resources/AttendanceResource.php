<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources;

use Bishopm\Church\Filament\Clusters\Admin;
use Bishopm\Church\Filament\Clusters\Admin\Resources\AttendanceResource\Pages;
use Bishopm\Church\Filament\Clusters\Admin\Resources\AttendanceResource\RelationManagers;
use Bishopm\Church\Models\Attendance;
use Bishopm\Church\Models\Individual;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Admin::class;

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('attendancedate')
                    ->label('Date')
                    ->default('last Sunday')
                    ->native(false)
                    ->displayFormat('Y-m-d')
                    ->format('Y-m-d')
                    ->required(),
                Forms\Components\Select::make('service')
                    ->required()
                    ->options(fn () => array_combine(setting('general.services'),setting('general.services')))
                    ->placeholder('')
                    ->default(function () {
                        $latest = Attendance::orderBy('id','DESC')->first();
                        return $latest->service;
                    }),
                Forms\Components\TextInput::make('scanbox')
                    ->label('Scan barcode')
                    ->autofocus()
                    ->live(debounce: 400)
                    ->afterStateUpdated(function (Set $set, Get $get, $state, $livewire) {
                        if ($state) {
                            $date=date('Y-m-d',strtotime($get('attendancedate')));
                            $service=$get('service');
                            $indiv=Individual::find($state);
                            $check = Attendance::where('attendancedate',$date)->where('service',$service)->where('individual_id',$state)->first();
                            if (!$check){
                                $set('individual_id', intval($state));
                                $msg = $indiv->firstname . " " . $indiv->surname . " attended the " . $service . " service on " . $date;
                                Notification::make('notify')->title($msg)->send();
                                Attendance::create([
                                    'attendancedate' => $date,
                                    'service' => $service,
                                    'individual_id' => $state
                                ]);
                                $set('individual_id', '');
                                $set('scanbox', '');
                            } else {
                                Notification::make('notify')->title($indiv->firstname . " " . $indiv->surname . " has already been scanned for this service")->send();
                            }
                        }
                    }),
                Forms\Components\Select::make('individual_id')
                    ->label('Individual')
                    ->options(Individual::orderBy('firstname')->get()->pluck('fullname', 'id'))
                    ->searchable()
                    ->live(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('attendancedate')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('service')
                    ->searchable(),
                Tables\Columns\TextColumn::make('individual.fullname')
                    ->numeric()
                    ->sortable(),
            ])
            ->defaultSort('attendanceDate','DESC')
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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
