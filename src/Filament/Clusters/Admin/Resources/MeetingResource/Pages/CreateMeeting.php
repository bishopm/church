<?php

namespace Bishopm\Church\Filament\Clusters\Admin\Resources\MeetingResource\Pages;

use Bishopm\Church\Filament\Clusters\Admin\Resources\MeetingResource;
use Bishopm\Church\Models\Agendaitem;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMeeting extends CreateRecord
{
    protected static string $resource = MeetingResource::class;

    protected function afterCreate(): void
    {
        $id = $this->record->id;
        if ($this->data['agenda']<>""){
            $items=explode(',',$this->data['agenda']);
            foreach ($items as $ndx=>$item){
                Agendaitem::create([
                    'meeting_id' => $id,
                    'heading' => $item,
                    'sortorder' => $ndx,
                    'level' => 1
                ]);
            }
        }
    }
}
