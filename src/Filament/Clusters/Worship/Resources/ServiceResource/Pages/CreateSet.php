<?php

namespace Bishopm\Church\Filament\Clusters\Worship\Resources\ServiceResource\Pages;

use Bishopm\Church\Filament\Clusters\Worship\Resources\ServiceResource;
use Bishopm\Church\Models\Setitem;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;


class CreateSet extends CreateRecord
{
    protected static string $resource = ServiceResource::class;

    protected function afterCreate(): void
    {
        $id = $this->record->id;
        $settings=setting('worship.order_of_service');
        $items=explode(',',$settings[$this->record->servicetime]);
        foreach ($items as $ndx=>$item){
            Setitem::create([
                'service_id' => $id,
                'note' => $item,
                'sortorder' => $ndx
            ]);
        }
    }
}
