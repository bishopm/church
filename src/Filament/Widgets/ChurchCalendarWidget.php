<?php

namespace Bishopm\Church\Filament\Widgets;

use Guava\Calendar\Widgets\CalendarWidget;
use Bishopm\Church\Models\Diaryentry;
use Bishopm\Church\Models\Tenant;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Guava\Calendar\Actions\CreateAction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Model;


class ChurchCalendarWidget extends CalendarWidget
{
    protected string $calendarView = 'timeGridWeek';

    protected bool $eventClickEnabled = true;

    protected bool $eventDragEnabled = true;

    protected bool $eventResizeEnabled = true;

    public ?Model $record = null;

    protected bool $dateSelectEnabled = true;

    public function getEvents(array $fetchInfo = []): Collection | array
    {
        return collect()
            ->push(...Diaryentry::query()->where('venue_id',$this->record->id)->get())
        ;
    }

    public function getEventContent(): null | string | array
    {
        return [
            Diaryentry::class => view('church::components.diaryentry'),
        ];
    }

    public function getHeaderActions(): array
    {
        return [
                Action::make('report')->label('Weekly report')
                    ->form([
                        DatePicker::make('reportdate')
                            ->label('Starting date')
                            ->format('Y-m-d')
                            ->displayFormat('Y-m-d')
                            ->weekStartsOnMonday()
                            ->default(now())
                            ->required(),
                    ])
                    ->action(function (array $data): void {
                        redirect()->route('reports.venue', ['id' => $this->record, 'reportdate'=>$data['reportdate']]);
                    }),
                CreateAction::make('createDiaryentry')->label('Add booking')
                    ->model(Diaryentry::class),
        ];
    }

    public function getEventClickContextMenuActions(): array
    {
        return [
            $this->editAction(),
            $this->deleteAction(),
        ];
    }

    public function getDateSelectContextMenuActions(): array
    {
        return [
            CreateAction::make('ctxCreateDiaryentry')
                ->model(Diaryentry::class)
                ->mountUsing(function (Form $form, array $arguments) {
                    $tenant = data_get($arguments, 'diarisable_id');
                    $diarydatetime = data_get($arguments, 'startStr');
                    $endtime = data_get($arguments, 'endStr');
                    if ($diarydatetime) {
                        $form->fill([
                            'diarisable_id' => $tenant,
                            'diarydatetime' => Carbon::make($diarydatetime),
                            'endtime' => Carbon::make($endtime),
                            'venue_id' => $this->record->id
                        ]);
                    }
                }),
        ];
    }

    public function getTimeClickContextMenuActions(): array
    {
        return [
            CreateAction::make('ctxCreateDiaryentry')
                ->model(Diaryentry::class)
                ->mountUsing(function (Form $form, array $arguments) {
                    $date = data_get($arguments, 'dateStr');

                    if ($date) {
                        $form->fill([
                            'diarydatetime' => Carbon::make($date),
                            'endtime' => Carbon::make($date),
                        ]);
                    }
                }),
        ];
    }


    public function getSchema(?string $model = null): ?array
    {
        return 
            [
                Select::make('diarisable_id')
                    ->label('Venue user')
                    ->options(Tenant::orderBy('tenant')->get()->pluck('tenant', 'id'))
                    ->searchable()
                    ->required(),
                Hidden::make('venue_id')
                    ->default($this->record->id),
                Group::make([
                    DateTimePicker::make('diarydatetime')
                        ->native(true)
                        ->seconds(false)
                        ->displayFormat('Y-m-d H:i')
                        ->format('Y-m-d H:i')
                        ->required(),
                    TimePicker::make('endtime')
                        ->native(true)
                        ->seconds(false)
                        ->required(),
                ])->columns(),
            ];
    }

    public function onEventDrop(array $info = []): bool
    {
        parent::onEventDrop($info);

        if (in_array($this->getModel(), [Diaryentry::class])) {
            $record = $this->getRecord();

            if ($delta = data_get($info, 'delta')) {
                $startsAt = $record->diarydatetime;
                $startsAt = date("Y-m-d H:i",strtotime($startsAt) + $delta['seconds']);
                $endsAt = $record->endtime;
                $endsAt = date("H:i",strtotime($endsAt) + $delta['seconds']);
                $record->update([
                    'diarydatetime' => $startsAt,
                    'endtime' => $endsAt,
                ]);

                Notification::make()
                    ->title('Event date moved!')
                    ->success()
                    ->send()
                ;
            }
            return true;
        }

        return false;
    }

    public function onEventResize(array $info = []): bool
    {
        parent::onEventResize($info);

        if ($this->getModel() === Diaryentry::class) {
            $record = $this->getRecord();
            if ($delta = data_get($info, 'endDelta')) {
                $endsAt = $record->endtime;
                $endsAt = date("H:i",strtotime($endsAt) + $delta['seconds']);
                $record->update([
                    'endtime' => $endsAt,
                ]);
            }

            Notification::make()
                ->title('Event duration changed!')
                ->success()
                ->send()
            ;

            return true;

        }

        Notification::make()
            ->title('Duration of this event cannot be changed!')
            ->danger()
            ->send()
        ;

        return false;
    }

    public function authorize($ability, $arguments = [])
    {
        return true;
    }

    public function getOptions(): array
    {
        return [
            'slotMinTime' => '07:00:00',
            'slotMaxTime' => '21:00:00',
            'headerToolbar' => [
                'start' => 'title',
                'center' => 'dayGridMonth,timeGridWeek,timeGridDay',
                'end' => 'today prev,next',
            ]
        ];
    }

}