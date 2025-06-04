<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources\TaskResource\Pages;

use Bishopm\Church\Filament\Clusters\Admin\Resources\TaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTasks extends ListRecords
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Task board')->url(route('filament.admin.admin.resources.tasks.taskboard')),
            Actions\Action::make('Recurring tasks')->url(fn (): string => route('filament.admin.admin.resources.recurringtasks.index')),
            Actions\CreateAction::make(),
        ];
    }
}
