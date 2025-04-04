<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources\IndividualResource\Pages;

use Bishopm\Church\Filament\Clusters\People\Resources\IndividualResource;
use Bishopm\Church\Models\Anniversary;
use Bishopm\Church\Models\Household;
use Bishopm\Church\Models\Individual;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Redirect;

class EditIndividual extends EditRecord
{
    protected static string $resource = IndividualResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->form([
                Select::make('deleteReason')
                    ->label('Why is ' . $this->record->firstname . ' being removed?')
                    ->options([
                        'left' => $this->record->firstname . ' is no longer a member of the church',
                        'death' => $this->record->firstname . ' has died'
                    ])
                    ->live()
                    ->required(),
                DatePicker::make('anniversarydate')->label('Date of death')
                    ->native(false)
                    ->displayFormat('Y-m-d')
                    ->format('Y-m-d')
                    ->hidden(fn (Get $get): bool => !($get('deleteReason') == "death")),
                Toggle::make('deleteHousehold')
                    ->label($this->record->firstname . ' is the only member of this household. Should we delete the household too?')
                    ->hidden(function () {
                        $household = Household::with('individuals')->where('id',$this->record->household_id)->first();
                        if (count($household->individuals)==1){
                            return false;
                        } else {
                            return true;
                        }
                    }),               
            ])
            ->action(function (array $data): void {
                $individual=Individual::with('groups','household','pastors','rosteritems','pastoralnotes')->where('id',$this->record->id)->first();
                $individual->groups()->detach();
                $individual->pastoralnotes()->delete();
                $individual->rosteritems()->delete();
                $individual->pastors()->delete();
                if (isset($data['deleteHousehold']) and ($data['deleteHousehold']==true)){
                    $individual->household()->delete();
                } elseif (isset($data['anniversarydate']) and ($data['anniversarydate']<>"")){
                    Anniversary::create([
                        'household_id'=>$this->record->household_id,
                        'anniversarytype'=>'Death',
                        'anniversarydate'=>$data['anniversarydate'],
                        'details'=>$this->record->firstname . '\'s death'
                    ]);
                }
                $individual->delete();
                Notification::make()
                    ->title('Record deleted')
                    ->body('The record has been deleted successfully..')
                    ->success()
                    ->send();
                Redirect::route('filament.admin.people.resources.individuals.index');
            })
        ];
    }

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }

}
