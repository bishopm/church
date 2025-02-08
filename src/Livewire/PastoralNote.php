<?php
 
namespace Bishopm\Church\Livewire;

use Livewire\Component;
 
class PastoralNote extends Component
{
    public $pastor_id;
    public $pastoralnotable_id;
    public $pastoralnotable_type;


    public function mount($pastor_id,$pastoralnotable_id, $pastoralnotable_type) {

        $this->pastor_id = $pastor_id;
        $this->pastoralnotable_id = $pastoralnotable_id;
        $this->pastoralnotable_type = $pastoralnotable_type;
    }

    public function render()
    {
        return view('church::livewire.pastoralnote');
    }
}