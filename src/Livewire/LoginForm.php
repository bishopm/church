<?php
 
namespace Bishopm\Church\Livewire;

use Bishopm\Church\Models\Individual;
use Livewire\Component;
 
class LoginForm extends Component
{
    public $phone = '';
    public $message = '';
    public $error = '';
    public $pin = '';
    public $hashed = '';
    public $pinsent = false;
    public $userpin = '';
    public $result = '';
    public $final = false;
 
    public function send(){
        if (strlen($this->phone) !== 10){
            $this->error = "Error: The number should be 10 digits long";
        } elseif (!is_numeric($this->phone)) {
            $this->error = "Error: Only numbers - no letters should be included";
        } else {
            $indiv=Individual::where('cellphone',$this->phone)->get();
            if (count($indiv)>1){
                $this->error="This number is associated with more than one person. Please contact our office and we'll help you to complete your registration.";
            } elseif (count($indiv)==1){
                $this->error = "";
                $this->message = "Hello, " . $indiv[0]->firstname . "! We are sending you an SMS now.";
                $this->pin = rand(1000,9999);
                $this->hashed = hash('sha256', $this->pin);
                $this->pinsent = true;
            } else {
                $this->error = "";
                $this->message = "We don't have your name in our system yet. Please enter your name and press OK";
            }
        }
    }

    public function sendpin(){
        if ($this->userpin == $this->pin){
            setcookie('wmc-access',$this->hashed, 2147483647,'/');
            setcookie('wmc-mobile',$this->phone, 2147483647,'/');
            return redirect('/')->with('status', 'Welcome! You are now logged in');
        } else {
            $this->result = "Wrong! Please try again!";
        }
    }
 
    public function render()
    {
        return view('church::livewire.login');
    }
}