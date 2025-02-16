<?php

namespace Bishopm\Church\Filament\Clusters\Resources\Resources\CourseResource\Pages;

use Bishopm\Church\Filament\Clusters\Resources\Resources\CourseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCourses extends ListRecords
{
    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
