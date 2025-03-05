<?php
 
namespace Bishopm\Church\Livewire;

use Bishopm\Church\Events\NewLiveMessage;
use Bishopm\Church\Events\NewUserMessage;
use Bishopm\Church\Models\Individual;
use Bishopm\Church\Models\Message;
use Livewire\Component;

class Live extends Component
{

    public array $messages;
    public array $members;
    public $service;
    public string $usermessage;
    public int $id;

    protected $listeners  = ['updateMessages'];

    public function mount($id){
        $this->id = $id;
    }

    public function updateMessages()
    {
        $indiv=Individual::find($this->id);
        $this->messages = Message::with('individual')->where('messages.messagetime','>',$indiv->online)->orderBy('messages.messagetime')->get()->toArray();
    }

    public function sendMessage(){
        $new = Message::create([
            'individual_id' => $this->id,
            'message' => $this->usermessage
        ]);
        $this->updateMessages();
        NewLiveMessage::dispatch($this->id,$this->usermessage);
        $this->usermessage="";
    }

    public function render()
    {
        return view('church::livewire.live');
    }
}