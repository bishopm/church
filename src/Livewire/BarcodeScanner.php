<?php
 
namespace Bishopm\Church\Livewire;

use Bishopm\Church\Models\Book;
use Bishopm\Church\Models\Loan;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\Attributes\On;
 
class BarcodeScanner extends Component
{

    public $barcode="";
    public $title="";
    public $authors="";
    public $image="";
    public $publisher="";
    public $description="";
    public $aarray=[];
    public $create=false;
    public $isbn="";
    public $id="";

    #[On('scanned')] 
    public function scanComplete($isbn) {
        $book=Book::where('isbn',$isbn)->first();
        if ($book){
            $this->title=$book->title;
            $this->authors=implode(',',$book->authors[0]);
            $this->image=$book->image;
            $this->id=$book->id;
        } else {
            $url="https://www.googleapis.com/books/v1/volumes?key=" . setting('features.google_books_key'). "&q=isbn:" . $isbn;
            $response=Http::get($url);
            $book=json_decode($response->body())->items[0]->volumeInfo;
            $this->isbn=$isbn;
            if (isset($book->subtitle)){
                $this->title=$book->title . ": " . $book->subtitle;
            } else {
                $this->title=$book->title;
            }
            if (isset($book->publisher)){
                $this->publisher=$book->publisher;
            }
            if (isset($book->description)){
                $this->description=$book->description;
            }
            $this->image=$book->imageLinks->thumbnail;
            foreach ($book->authors as $author){
                $this->aarray[]=['name'=>$author];
            }
            $this->authors=implode(',',$this->aarray[0]);
            $this->create = true;
        }
    }

    public function saveBorrow($member) {
        if ($this->create){
            $new=Book::create([
                'title' => $this->title,
                'description' => $this->description,
                'authors' => $this->aarray,
                'publisher' => $this->publisher,
                'image' => $this->image,
                'isbn' => $this->isbn
            ]);
            $this->id=$new->id;
        }
        $borrow=Loan::create([
            'individual_id' => $member,
            'book_id' => $this->id,
            'duedate' => date('Y-m-d',strtotime('+ 2 weeks'))
        ]);
        session()->flash('message','Please return this in 2 weeks');
        return redirect('books');
    }

    public function render()
    {
        return view('church::livewire.barcodescanner');
    }
}