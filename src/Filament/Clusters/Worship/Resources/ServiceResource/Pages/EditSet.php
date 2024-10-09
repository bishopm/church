<?php

namespace Bishopm\Church\Filament\Clusters\Worship\Resources\ServiceResource\Pages;

use Bishopm\Church\Filament\Clusters\Worship\Resources\ServiceResource;
use Bishopm\Church\Models\Setitem;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSet extends EditRecord
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Order of service')->url(fn (): string => route('reports.service', ['id' => $this->record])),
            $this->getSaveFormAction()->formId('form'),
            Actions\DeleteAction::make()
                ->before(function () {
                    $setitems = Setitem::where('service_id',$this->record->id)->delete();
                })
        ];
    }
}
