<?php
 
namespace Bishopm\Church\Livewire;

use Livewire\Component;
 
class BookReview extends Component
{
    public $review='';

    public function send(){
        //
    }

    public function render()
    {
        return view('church::livewire.bookreview');
    }
}