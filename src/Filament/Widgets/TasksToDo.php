<?php

namespace Bishopm\Church\Filament\Widgets;

use Bishopm\Church\Models\Individual;
use Bishopm\Church\Models\Task;
use Bishopm\Church\Models\User;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class TasksToDo extends Widget implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    protected static string $view = 'church::widgets.tasks-to-do';

    public array $tasks;
    public $individual_id;

    function mount() {
        $indiv = Individual::where('user_id',Auth::user()->id)->first();
        $this->individual_id=$indiv->id;
        if ($indiv){
            $this->tasks=Task::where('individual_id',$indiv->id)->where('status','todo')->orderBy('duedate','asc')->take(5)->get()->toArray();
        } else {
            $this->tasks=array();
        }
    }

    public function addAction(): Action {

        return Action::make('add')
            ->label('Add a task')
            ->form([
                TextInput::make('description')->required(),
                Select::make('individual_id')
                    ->label('Assigned to')
                    ->options(User::with('individual')->orderBy('name')->get()->pluck('name', 'individual.id'))
                    ->default($this->individual_id)
                    ->searchable(),
                DatePicker::make('duedate')
                    ->native(false)
                    ->displayFormat('Y-m-d')
                    ->format('Y-m-d')
                    ->label('Due date')
                    ->default(date('Y-m-d',strtotime('+7 days')))
                    ->required(),
                Select::make('status')->options([
                    'todo' => 'To do',
                    'doing' => 'Underway',
                    'done' => 'Done'
                ])
                ->default('todo'),
                Select::make('visibility')->options([
                    'public' => 'Public',
                    'private' => 'Private'
                ])
                ->default('public')
            ])
            ->action(function (array $data) {
                Task::create([
                    'description' => $data['description'],
                    'status' => $data['status'],
                    'duedate' => $data['duedate'],
                    'visibility' => $data['visibility'],
                    'individual_id' => $data['individual_id']
                ]);
                $this->tasks=Task::where('individual_id',$this->individual_id)->where('status','todo')->orderBy('duedate','asc')->take(5)->get()->toArray();
            });
    }

    public static function canView(): bool 
    { 
        $roles =auth()->user()->roles->toArray(); 
        $permitted = array('Office','Finance');
        foreach ($roles as $role){
            if ((in_array($role['name'],$permitted)) or (auth()->user()->isSuperAdmin())){
                return true;
            }
        }
        return false;
    }

    public function done($id){
        $updatetask=Task::find($id);
        $updatetask->status="done";
        $updatetask->save();
        $this->mount();
    }
}
