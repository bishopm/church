<?php

namespace Bishopm\Church\Filament\Clusters\Resources\Resources\CourseResource\RelationManagers;

use Bishopm\Church\Models\Course;
use Bishopm\Church\Models\Coursesession;
use Bishopm\Church\Models\Diaryentry;
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
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('sessiondate')
                    ->label('Date and time')
                    ->native(true)
                    ->seconds(false)
                    ->displayFormat('Y-m-d H:i')
                    ->format('Y-m-d H:i'),
                Forms\Components\TimePicker::make('endtime')
                    ->label('End time')
                    ->seconds(false),
                Forms\Components\FileUpload::make('file')
                    ->directory('course')
                    ->disk('google'),
                Forms\Components\FileUpload::make('leadernotes')->label('Leader notes')
                    ->directory('course')
                    ->disk('google'),
                Forms\Components\TextInput::make('video')
                    ->suffixAction(MediaAction::make('showVideo')
                        ->icon('heroicon-m-video-camera')),
                Forms\Components\Checkbox::make('calendar')
                    ->label('Add to church calendar'),
                Forms\Components\RichEditor::make('notes')
                    ->columnSpanFull()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('session')
            ->columns([
                Tables\Columns\TextColumn::make('order')->label('Week'),
                Tables\Columns\TextColumn::make('session'),
                Tables\Columns\TextColumn::make('sessiondate')->label('Date and time'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data, RelationManager $livewire): array {
                        $course=Course::with('coursesessions')->where('id',$livewire->getOwnerRecord()->id)->first();
                        $data['order'] = 1+count($course->coursesessions);
                        return $data;
                    })
                    ->after(function (Coursesession $session, RelationManager $livewire) {
                        if (($session['sessiondate']) and ($session['endtime'])){
                            Diaryentry::create([
                                'diarisable_type' => 'course',
                                'diarisable_id' => $livewire->getOwnerRecord()->id,
                                'venue_id' => $livewire->getOwnerRecord()->venue_id,
                                'details' => $livewire->getOwnerRecord()->course . " (week " . $session['order'] . ")",
                                'diarydatetime' => $session['sessiondate'],
                                'endtime' => $session['endtime']
                            ]);
                        }
                    }),
            ])
            ->reorderable('order')
            ->defaultSort('order')
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
