<?php

namespace Bishopm\Church\Filament\Clusters\Resources\Resources\CourseResource\Pages;

use Bishopm\Church\Filament\Clusters\Resources\Resources\CourseResource;
use Bishopm\Church\Models\Diaryentry;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCourse extends CreateRecord
{
    protected static string $resource = CourseResource::class;

}
