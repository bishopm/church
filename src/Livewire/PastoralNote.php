<?php
 
namespace Bishopm\Church\Livewire;

use Livewire\Component;
 
class PastoralNote extends Component
{
    public $pastor_id;
    public $pastoralnotable_id;
    public $pastoralnotable_type;
    public $case;
    public $mostrecent;
    public $detail;
    public $details='';


    public function mount($pastor_id,$pastoralnotable_id, $pastoralnotable_type, $case, $mostrecent, $detail) {

        $this->pastor_id = $pastor_id;
        $this->pastoralnotable_id = $pastoralnotable_id;
        $this->pastoralnotable_type = $pastoralnotable_type;
        $this->case=$case;
        $this->mostrecent=$mostrecent;
        $this->detail=$detail;
    }

    public function save(){
        dd($this->details);
    }

    public function render()
    {
        return view('church::livewire.pastoralnote');
    }
}