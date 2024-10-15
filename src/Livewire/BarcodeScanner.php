<?php
 
namespace Bishopm\Church\Livewire;

use Bishopm\Church\Models\Book;
use Livewire\Component;
 
class BarcodeScanner extends Component
{

    public $barcode="";

    public function updatedBarcode() {
        dd($this->barcode);
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