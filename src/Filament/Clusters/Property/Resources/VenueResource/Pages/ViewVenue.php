<?php

namespace Bishopm\Church\Filament\Clusters\Property\Resources\VenueResource\Pages;

use Bishopm\Church\Filament\Clusters\Property\Resources\VenueResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Form;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Bishopm\Church\Filament\Widgets\ChurchCalendarWidget;

class ViewVenue extends ViewRecord
{
    protected static string $resource = VenueResource::class;

    protected function getFooterWidgets(): array {
        return [
            \Bishopm\Church\Filament\Widgets\ChurchCalendarWidget::class,
        ];
    }

}
