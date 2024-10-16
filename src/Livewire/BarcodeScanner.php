<?php
 
namespace Bishopm\Church\Livewire;

use Bishopm\Church\Models\Book;
use Livewire\Component;
use Livewire\Attributes\On;
 
class BarcodeScanner extends Component
{

    public $barcode="";

    #[On('scanned')] 
    public function scanComplete($isbn) {
        dd($isbn);
        $book=Book::where('isbn',$this->barcode)->first();
        if ($book){
            dd($book);
        } else {
            dd($this->barcode);
        }
    }

    public function render()
    {
        return view('church::livewire.barcodescanner');
    }
}