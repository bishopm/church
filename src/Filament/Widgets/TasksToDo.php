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
use Filament\Support\Enums\ActionSize;
use Illuminate\Support\Facades\DB;

class TasksToDo extends Widget implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    protected static string $view = 'church::widgets.tasks-to-do';

    public array $tasks, $doings, $somedays, $dones, $projects;
    public $tcount,$ucount,$scount,$dcount, $pcount;
    public $individual_id;

    function mount() {
        $indiv = Individual::where('user_id',Auth::user()->id)->first();
        $this->individual_id=$indiv->id;
        if ($indiv){
            $this->getTasks();
        } else {
            $this->tasks=array();
            $this->tcount=0;
            $this->doings=array();
            $this->ucount=0;
            $this->somedays=array();
            $this->scount=0;
            $this->dones=array();
            $this->dcount=0;
        }
    }

    private function getTasks(){
        $this->tasks=Task::where('individual_id',$this->individual_id)->where('status','todo')->orderBy('duedate','asc')->get()->toArray();
        $this->tcount=count($this->tasks);
        $this->tasks = array_slice($this->tasks, 0, 5, true);
        $this->doings=Task::where('individual_id',$this->individual_id)->where('status','doing')->orderBy('duedate','asc')->get()->toArray();
        $this->ucount=count($this->doings);
        $this->doings = array_slice($this->doings, 0, 5, true);
        $this->somedays=Task::where('individual_id',$this->individual_id)->where('status','someday')->orderBy('duedate','asc')->get()->toArray();
        $this->scount=count($this->somedays);
        $this->somedays = array_slice($this->somedays, 0, 5, true);
        $this->dones=Task::where('individual_id',$this->individual_id)->where('status','done')->orderBy('duedate','asc')->get()->toArray();
        $this->dcount=count($this->dones);
        $this->dones = array_slice($this->dones, 0, 5, true);
        $tags=DB::table('tags')->where('type','tasks')->orderBy('name','ASC')->get()->toArray();
        $alltags=array();
        foreach ($tags as $tag){
            $alltags[]=json_decode($tag->name)->en;
        }
        $projecttasks=Task::withAnyTags($alltags,'tasks')->with('tags')->where('status','todo')->orderBy('duedate','asc')->get();
        foreach ($projecttasks as $ptask){
            $this->projects[$ptask->tags[0]->name][]=$ptask;
        }
        $this->pcount=count($this->projects);
    }

    public function addAction(): Action {

        return Action::make('add')
            ->tooltip('Add a task')
            ->icon('heroicon-s-plus-circle')
            ->size(ActionSize::Large)
            ->iconButton()
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
                    'someday' => 'Some day',
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
                if ($data['status']=="todo"){
                    $this->tasks=Task::where('individual_id',$this->individual_id)->where('status','todo')->orderBy('duedate','asc')->take(5)->get()->toArray();
                    $this->tcount++;
                } elseif ($data['status']=="doing"){
                    $this->doings=Task::where('individual_id',$this->individual_id)->where('status','doing')->orderBy('duedate','asc')->take(5)->get()->toArray();
                    $this->ucount++;
                } elseif ($data['status']=="someday"){
                    $this->somedays=Task::where('individual_id',$this->individual_id)->where('status','someday')->orderBy('duedate','asc')->take(5)->get()->toArray();
                    $this->scount++;
                }
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
        if ($updatetask->agendaitem_id){
            $updatetask->statusnote="Completed on " . date('Y-m-d');
        }
        $updatetask->save();
        $this->getTasks();
    }

    public function undone($id){
        $updatetask=Task::find($id);
        $updatetask->status="todo";
        $updatetask->save();
        $this->getTasks();
    }
}
