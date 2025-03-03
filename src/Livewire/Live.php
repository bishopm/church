<?php
 
namespace Bishopm\Church\Livewire;

use Bishopm\Church\Models\Individual;
use Livewire\Component;

class Live extends Component
{

    public array $messages;

    public function render()
    {
        return view('church::livewire.live');
    }
}