<?php

namespace Bishopm\Church\Filament\Clusters\Property\Resources\TenantResource\RelationManagers;

use Bishopm\Church\Models\Diaryentry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DiaryentriesRelationManager extends RelationManager
{
    protected static string $relationship = 'diaryentries';

    protected static ?string $title = 'Venue bookings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DateTimePicker::make('diarydatetime')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('group')
            ->columns([
                Tables\Columns\TextColumn::make('diarydatetime')->label('Date and start time')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('endtime')->label('End time'),
                Tables\Columns\TextColumn::make('venue.venue')->label('Venue'),
            ])
            ->filters([
                Filter::make('future_bookings_only')
                ->query(fn (Builder $query): Builder => $query->where('diarydatetime', '>=', now()))
                ->default()
            ])
            ->headerActions([
            ])
            ->recordUrl(fn (Diaryentry $record): string => route('filament.admin.property.resources.venues.view', $record->venue_id))
            ->actions([
                Tables\Actions\ViewAction::make('venueAction')
                    ->url(fn (Diaryentry $record): string => route('filament.admin.property.resources.venues.view', $record->venue_id))
                    ->label('Go to venue'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
