<?php
 
namespace Bishopm\Church\Livewire;
 
use Livewire\Component;
 
class LoginForm extends Component
{
    public $phone = '';
 
    public function save()
    {
        dd($phone);
        session()->flash('status', 'Post successfully updated.');
 
        return $this->redirect('/login');
    }
 
    public function render()
    {
        return view('church::livewire.login');
    }
}