<?php
 
namespace Bishopm\Church\Livewire;

use Livewire\Component;
 
class BarcodeScanner extends Component
{

    public function mount() {
        // On mount
    }

    public function render()
    {
        return view('church::livewire.barcodescanner');
    }
}