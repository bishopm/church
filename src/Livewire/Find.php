<?php
 
namespace Bishopm\Church\Livewire;

use Bishopm\Church\Models\Household;
use Bishopm\Church\Models\Individual;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Find extends Component
{
    public $name = '';
    public $names = [];

    public function updatedName($val){
        $this->names = Individual::with('household')->where(DB::raw('concat(firstname," ",surname)') , 'LIKE' , '%' . $val . '%')->whereNotNull('firstname')->limit(15)->get();
    }

    public function render()
    {
        return view('church::livewire.find');
    }
}