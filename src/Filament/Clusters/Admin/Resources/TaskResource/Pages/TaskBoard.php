<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources\TaskResource\Pages;

use Bishopm\Church\Filament\Clusters\Admin\Resources\TaskResource;
use Bishopm\Church\Models\Individual;
use Bishopm\Church\Models\Task;
use Bishopm\Church\Models\User;
use Filament\Actions\Action;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Set;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class TaskBoard extends Page implements HasForms, HasActions {

    protected static string $resource = TaskResource::class;

    protected static ?string $title = 'Task board';

    protected static string $view = 'church::taskboard';

    public $tasks;
    public $statuses;
    public $individual_id;
    public $tab;

    public function mount(){
        $indiv = Individual::where('user_id',Auth::user()->id)->first();
        $this->individual_id=$indiv->id;
        $this->getTasks();
    }

    private function getTasks(){
        $tasks=Task::where('status','<>','done')->get();
        foreach ($tasks as $task){
            $fin[$task->status][]=$task;
            if ($task->tags){
                foreach ($task->tags as $project){
                    $fin['projects'][$project->name][]=$task;
                }
            }
        }
        $statuses=[
            'projects'=>'Projects',
            'doing'=>'Underway',
            'todo'=>'To do',
            'someday'=>'Some day'
        ];
        $this->statuses=$statuses;
        $this->tasks=$fin;
    }

    public function editAction(): Action {

        return Action::make('edit')
            ->fillForm(function ($arguments){
                $task = Task::find($arguments['task']);
                return [
                    'id' => $task->id,
                    'description' => $task->description,
                    'individual_id' => $task->individual_id,
                    'duedate' => $task->duedate,
                    'status' => $task->status,
                    'visibility' => $task->visibility,
                    'statusnote' => $task->statusnote,
                    'tags' => $task->tags()->pluck('id')
                ];
            })
            ->record(function ($arguments){
                return Task::find($arguments['task']);
            })
            ->form([
                Hidden::make('id'),
                TextInput::make('description')->required(),
                Select::make('individual_id')
                    ->label('Assigned to')
                    ->options(Individual::orderBy('firstname')->get()->pluck('name', 'id'))
                    ->default($this->individual_id)
                    ->searchable(),
                DatePicker::make('duedate')
                    ->native(false)
                    ->displayFormat('Y-m-d')
                    ->format('Y-m-d')
                    ->label('Due date')
                    ->default(date('Y-m-d',strtotime('+7 days'))),
                Select::make('status')->options([
                    'todo' => 'To do',
                    'doing' => 'Underway',
                    'someday' => 'Some day',
                    'done' => 'Done'
                ])
                ->default('todo'),
                TextInput::make('statusnote'),
                Select::make('visibility')->options([
                    'public' => 'Public',
                    'private' => 'Private'
                ])
                ->default('public'),
                Select::make('tags')->label('Project')
                    ->relationship('tags','name',modifyQueryUsing: fn (Builder $query) => $query->where('type','task'))
                    ->multiple()
                    ->createOptionForm([
                        Grid::make()
                            ->columns(2)
                            ->schema([
                                TextInput::make('name')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                    ->required(),
                                TextInput::make('type')
                                    ->default('task')
                                    ->readonly()
                                    ->required(),
                                TextInput::make('slug')
                                    ->required(),
                            ])
                    ]),
            ])
            ->action(function (array $data) {
                $task=Task::find($data['id']);
                $task->update($data);
            });
    }

    public function done($id){
        $updatetask=Task::find($id);
        $updatetask->status="done";
        if ($updatetask->agendaitem_id){
            $updatetask->statusnote="Completed on " . date('Y-m-d');
        }
        $updatetask->save();
        $this->getTasks();
    }

}


