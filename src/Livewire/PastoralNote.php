<?php
 
namespace Bishopm\Church\Livewire;

use Bishopm\Church\Models\Pastoralnote as Note;
use Livewire\Component;
 
class PastoralNote extends Component
{
    public $pastor;
    public $pastoralnotable_id;
    public $pastoralnotable_type;
    public $case;
    public $mostrecent;
    public $detail;
    public $details;
    public $pastoraldate;
    public $care;
    public $show=false;
    public $name;

    public function mount($pastor,$pastoralnotable_id, $pastoralnotable_type, $case, $mostrecent, $detail, $pastoraldate, $name) {

        $this->pastor = $pastor;
        $this->pastoralnotable_id = $pastoralnotable_id;
        $this->pastoralnotable_type = $pastoralnotable_type;
        $this->case=$case;
        $this->mostrecent=$mostrecent;
        $this->detail=$detail;
        $this->pastoraldate = $pastoraldate;
        $this->name = $name;
    }

    public function showform()
    {
        $this->show = ! $this->show;
    }

    public function changeDetails($value){
        $this->details = $value . " " . $this->name;
    }

    public function delete($del){
        $d=Note::find($del)->delete();
    }

    public function render()
    {
        return view('church::livewire.pastoralnote');
    }

    public function save(){
        $pn=Note::create([
            'pastoralnotable_id'=>$this->pastoralnotable_id,
            'pastoralnotable_type'=>$this->pastoralnotable_type,
            'pastoraldate'=>$this->pastoraldate,
            'pastor_id'=>$this->pastor->id,
            'details'=>$this->details
        ]);
    }
}