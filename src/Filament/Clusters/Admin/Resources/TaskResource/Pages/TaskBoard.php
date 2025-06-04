<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources\TaskResource\Pages;

use Bishopm\Church\Filament\Clusters\Admin\Resources\TaskResource;
use Bishopm\Church\Models\Task;
use Filament\Resources\Pages\Page;

class TaskBoard extends Page {

    protected static string $resource = TaskResource::class;

    protected static ?string $title = 'Task board';

    protected static string $view = 'church::taskboard';

    public $tasks;

    public function mount(){
        $tasks=Task::all();
        foreach ($tasks as $task){
            $fin[$task->status][]=$task;
        }
        $this->tasks=$fin;
    }

}


