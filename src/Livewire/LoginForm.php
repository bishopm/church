<?php
 
namespace Bishopm\Church\Livewire;

use Bishopm\Church\Classes\BulksmsService;
use Bishopm\Church\Jobs\SendSMSNow;
use Bishopm\Church\Models\Household;
use Bishopm\Church\Models\Individual;
use Filament\Notifications\Notification;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Lukeraymonddowning\Honey\Traits\WithHoney;

class LoginForm extends Component
{
    use WithHoney;

    #[Validate('digits:10', message: 'Not a valid cellphone number')]
    public $phone = '';
    public $feedback = '';
    public $surname = '';
    public $firstname = '';
    public $message = '';
    public $pin = '';
    public $hashed = '';
    public $userpin = '';
    public $block_submit=true;
    public $showform=false;
    public $individual_id;
    public $override=0;
    public $master;

    protected $rules = [
        'phone' => 'required|digits:10',
        'firstname' => 'required',
        'surname' => 'required'
    ];

    public function toggleOverride(){
        if ($this->phone){
            $this->override="Enter Override PIN";
            $this->hashed = hash('sha256', $this->master);
            $indiv=Individual::where('cellphone',$this->phone)->first();
            $this->individual_id=$indiv->id;
            if (!$indiv->uid==null){
                $this->hashed=$indiv->uid;
            } else {
                $indiv->uid=$this->hashed;
                $indiv->save();
            }
            $this->pin=setting('admin.sms_master_pin');
        }
    }

    public function updated($propertyName)
    {
        if (($propertyName=="firstname") or ($propertyName=="surname")){
            if ((!$this->firstname) or (!$this->surname)){
                $this->block_submit=true;
            } else {
                $this->block_submit=false;
            }
        } elseif (count($this->validateOnly($propertyName))){
            if (substr($this->phone,0,1)!=='0'){
                $this->block_submit = true;
                $this->feedback="";
                $this->showform=false;
            } else {
                $indiv=Individual::where('cellphone',$this->phone)->get();
                if (count($indiv)>1){
                    $this->feedback="Sorry! This number is linked to more than one person in our record (possibly you and a family member). Please contact our office so that we can help you complete your app registration.";
                    $this->showform=false;
                    $this->block_submit = true;
                } elseif (count($indiv)==1){
                    $this->feedback="Hello <b>" . $indiv[0]->firstname . " " . $indiv[0]->surname . "!</b><br>Click the button above to verify your cell number.";
                    $this->showform=false;
                    $this->block_submit = false;
                } else {
                    $this->showform=true;
                    $this->feedback="Welcome! We don't have your details on record. Please enter your name and then press the button above";
                    $this->block_submit = true;
                }
            }
        } else {
            $this->feedback="";
            $this->showform=false;
            $this->block_submit = true;
        }
    }

    public function sendsms(){
        if ($this->honeyPasses()){
            $this->feedback="";
            $this->hashed = hash('sha256', $this->pin);
            $indiv=Individual::where('cellphone',$this->phone)->first();
            if (!$indiv){
                $household = Household::create(['addressee'=>$this->firstname . " " . $this->surname]);
                $indiv=Individual::create([
                    'firstname'=>$this->firstname,
                    'surname'=>$this->surname,
                    'cellphone'=>$this->phone,
                    'uid'=>$this->hashed,
                    'household_id'=>$household->id
                ]);
            } else {
                if ($indiv->uid){
                    $this->hashed=$indiv->uid;
                } else {
                    $indiv->uid=$this->hashed;
                    $indiv->save();
                }
            }
            $this->individual_id=$indiv->id;
            $this->pin = rand(1000,9999);
            $smss = new BulksmsService(setting('services.bulksms_clientid'), setting('services.bulksms_api_secret'));
            $credits = $smss->get_credits();
            if ($credits >0 ){
                $newno="+27" . substr($this->phone, 1);
                $body="Hi " . $indiv->firstname . ". Your 4 digit PIN number for the " . setting('general.church_abbreviation') . " app is " . $this->pin . "  ";
                $messages[]=array('to' => $newno, 'body' => $body);
                $smss->send_message($messages);
                $this->feedback="Your SMS is on it's way!";
            } else {
                $this->feedback="Sorry! There was a problem sending you an SMS. Please contact the office for help.";
            }
        }
    }

    public function sendpin(){
        $indiv=Individual::find($this->individual_id);
        if (($this->userpin == $this->pin) or ($this->master==setting('admin.sms_master_pin'))){
            setcookie('wmc-access',$this->hashed, 2147483647,'/');
            setcookie('wmc-id',$this->individual_id, 2147483647,'/');
            setcookie('wmc-mobile',$this->phone, 2147483647,'/');
            setcookie('wmc-version',setting('general.app_version'), 2147483647,'/');
            session()->flash('message', 'Welcome, ' . $indiv->firstname . '! You are now logged in on this device.');
            return redirect()->route('app.home');
        } else {
            session()->flash('message', 'Sorry! That PIN was not correct, you will need to try again. Contact the office if you need any help.');
            return redirect()->route('app.login');
        }
    }
 
    public function render()
    {
        return view('church::livewire.login');
    }
}