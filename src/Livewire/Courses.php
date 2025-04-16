<?php
 
namespace Bishopm\Church\Livewire;

use Bishopm\Church\Models\Course;
use Livewire\Component;

class Courses extends Component
{
    public $course;
    public $courses = [];

    public function mount(){
        $this->courses = Course::orderBy('course','ASC')->get();
    }

    public function updatedCourse($val){
        if ($this->course>''){
            $this->courses = Course::where('course', 'LIKE' , '%' . $val . '%')
            ->orWhereHas('tags', function ($q) use ($val){ $q->where('name', 'LIKE' , '%' . $val . '%');})
            ->orderBy('course','ASC')->get();
        } else {
            $this->courses = Course::orderBy('course','ASC')->get();
        }
    }

    public function render()
    {
        return view('church::livewire.courses');
    }
}