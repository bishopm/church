<?php

namespace Bishopm\Church\Filament\Widgets;

use Bishopm\Church\Models\Individual;
use Bishopm\Church\Models\Task;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class TasksToDo extends Widget
{
    protected static string $view = 'church::widgets.tasks-to-do';

    public array $tasks;

    function mount() {
        $indiv = Individual::where('user_id',Auth::user()->id)->first();
        if ($indiv){
            $this->tasks=Task::where('individual_id',$indiv->id)->where('status','todo')->orderBy('duedate','asc')->take(5)->get()->toArray();
        } else {
            $this->tasks=array();
        }
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
