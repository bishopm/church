<?php
 
namespace Bishopm\Church\Livewire;

use Bishopm\Church\Models\Book;
use Livewire\Component;
use Livewire\Attributes\On;
 
class BarcodeScanner extends Component
{

    public $barcode="";
    public $title="";
    public $authors="";

    #[On('scanned')] 
    public function scanComplete($isbn) {
        dd($isbn);
        $book=Book::where('isbn',$isbn)->first();
        dd($book);
        if ($book){
            $this->title=$book->title;
        } else {
            return $this->barcode;
        }
    }

    public function render()
    {
        return view('church::livewire.barcodescanner');
    }
}