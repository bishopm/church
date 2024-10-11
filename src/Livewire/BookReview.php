<?php
 
namespace Bishopm\Church\Livewire;

use Livewire\Component;
 
class BookReview extends Component
{
    public $rating=0;
    public $avgRating;
    public $book;
    public $key;
    public $member;

    public function mount($book, $key, $member) {

        $this->book = $book;
        $this->member= $member;
        $this->key = $key;
        $userRating = $this->book->comments()
            ->where('individual_id', $member)->first();

        if (!$userRating) {
            $this->rating = 0;
        } else {
            $this->rating = $userRating->pivot->rating;
        }

        $this->calculateAverageRating();
    }

    private function calculateAverageRating() {
        $this->avgRating = round($this->book->comments()->avg('rating'), 1);
    }

    public function render()
    {
        return view('church::livewire.bookreview');
    }

    public function setRating($val)
    {
        if ($this->rating == $val) {    // user can click on the same rating to reset the value
            $this->rating = 0;
        } else {
            $this->rating = $val;
        }

        $userRating = $this->book->comments()->where('individual_id', $this->member)->first();
        /*
        if (!$userRating) {
            $userRating = $this->book->comments()->attach($this->member, [
                'rating' => $val
            ]);
        } else {
            $this->book->users()->updateExistingPivot($userId, [
                'rating' => $val
            ], false);
        }
        */
        $this->calculateAverageRating();
    }
}