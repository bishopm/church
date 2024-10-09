<?php

namespace Bishopm\Church\Filament\Widgets;

use Bishopm\Church\Models\Individual;
use Bishopm\Church\Models\Task;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class TasksToDo extends Widget
{
    protected static string $view = 'church::widgets.tasks-to-do';

    public ?array $widgetdata;

    function mount() {
        $indiv = Individual::where('user_id',Auth::user()->id)->first();
        if ($indiv){
            Task::where('individual_id',$indiv->id)->orderBy('duedate','asc')->take(5)->get();
            $this->widgetdata['tasks']=Task::where('individual_id',$indiv->id)->orderBy('duedate','asc')->take(5)->get();
        } else {
            $this->widgetdata['tasks']=array();
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
}
