<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources\PastorResource\Pages;

use Bishopm\Church\Filament\Clusters\People\Resources\PastorResource;
use Bishopm\Church\Models\Individual;
use Bishopm\Church\Models\Pastor;
use Filament\Resources\Pages\Page;

class Pastoralcases extends Page {

    protected static string $resource = PastorResource::class;

    protected static ?string $title = 'Pastoral cases';

    protected static string $view = 'church::pastoralcases';

    public $data;

    public function mount(){
        $pastors=Pastor::with('households.pastoralnotes','individuals.pastoralnotes')->get();
        foreach ($pastors as $pastor){
            $newpastor = $pastor->individual->firstname . " " . $pastor->individual->surname;
            foreach ($pastor->individuals as $indiv){
                if (isset($data[$indiv->surname . $indiv->firstname][$indiv->id])){
                    if (!str_contains($data[$indiv->surname . $indiv->firstname][$indiv->id]['pastor'],$newpastor)){
                        $data[$indiv->surname . $indiv->firstname][$indiv->id]['pastor'].=", " . $newpastor;
                    }
                    $newcontact=$this->getContact($indiv->pastoralnotes);
                    if ($newcontact>$data[$indiv->surname . $indiv->firstname][$indiv->id]['contact']){
                        $data[$indiv->surname . $indiv->firstname][$indiv->id]['contact']=$newcontact;
                    }
                    $data[$indiv->surname . $indiv->firstname][$indiv->id]['notes']=$data[$indiv->surname . $indiv->firstname][$indiv->id]['notes'] + count($indiv->pastoralnotes);
                } else {
                    $data[$indiv->surname . $indiv->firstname][$indiv->id] =[
                        'name'=>$indiv->firstname . " " . $indiv->surname,
                        'type'=>'individual',
                        'contact'=>$this->getContact($indiv->pastoralnotes),
                        'pastor'=> $newpastor,
                        'notes'=>count($indiv->pastoralnotes)
                    ];
                }
            }
            foreach ($pastor->households as $house){
                if (isset($data[$house->sortsurname . $house->addressee][$house->id])){
                    if (!str_contains($data[$house->sortsurname . $house->addressee][$house->id]['pastor'],$newpastor)){
                        $data[$house->sortsurname . $house->addressee][$house->id]['pastor'].=", " . $newpastor;
                    }
                    $newcontact=$this->getContact($house->pastoralnotes);
                    if ($newcontact>$data[$house->sortsurname . $house->addressee][$house->id]['contact']){
                        $data[$house->sortsurname . $house->addressee][$house->id]['contact']=$newcontact;
                    }
                    $data[$house->sortsurname . $house->addressee][$house->id]['notes']=$data[$house->sortsurname . $house->addressee][$house->id]['notes'] + count($house->pastoralnotes);
                } else {
                    $data[$house->sortsurname . $house->addressee][$house->id] =[
                        'name'=>$house->addressee,
                        'type'=>'household',
                        'contact'=>$this->getContact($house->pastoralnotes),
                        'pastor'=>$newpastor,
                        'notes'=>count($house->pastoralnotes)
                    ];
                }
            }
        }
        ksort($data);
        $this->data=$data;
    }

    private function getContact($notes){
        $last = null;
        foreach ($notes as $note){
            if ($note->pastoraldate > $last){
                $last=$note->pastoraldate;
            }
        }
        return $last;
    }

}


