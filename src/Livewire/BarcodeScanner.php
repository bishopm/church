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
    public $image="";

    #[On('scanned')] 
    public function scanComplete($isbn) {
        $book=Book::where('isbn',$isbn)->first();
        if ($book){
            $this->title=$book->title;
            $this->authors=implode(',',$book->authors[0]);
            $this->image=$book->image;
        }
    }

    public function render()
    {
        return view('church::livewire.barcodescanner');
    }
}