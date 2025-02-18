<?php
 
namespace Bishopm\Church\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\GoogleCalendar\Event;

class Calendar extends Component
{
    public $events = [];
    public $status="Full church calendar";
    public $me;
    public $mth,$first;
    public $loaded=false;
 
    public function loadEvents()
    {
        $today=date('Y-m-d');
        $events=Event::get(new Carbon($today),new Carbon(date('Y-12-31',strtotime('+1 year'))));
        $this->events=[];
        $this->first=false;
        foreach ($events as $event){
            $this->me=$this->calendar_attend($event->description);
            if (($this->status!=="Full church calendar") or ($this->me=="yes")){
                if (is_null($event->startDateTime)){
                    $this->events[]=[
                        'id' => $event->id,
                        'name' => $event->name,
                        'date' => date('Y-m-d',strtotime($event->startDate)),
                        'time' => "",
                        'heading' => $this->makeheading(date('F',strtotime($event->startDate))),
                        'me' => $this->me
                    ];
                } else {
                    $this->events[]=[
                        'id' => $event->id,
                        'name' => $event->name,
                        'date' => date('Y-m-d',strtotime($event->startDateTime)),
                        'time' => date('H:i',strtotime($event->startDateTime)),
                        'heading' => $this->makeheading(date('F',strtotime($event->startDateTime))),
                        'me' => $this->me
                    ];
                }
            } else {}
        }
        $this->loaded = true;
    }

    private function makeheading($curr){
        if (!$this->first){
            $this->first=true;
            return true;
        } else {
            $ndx =  count($this->events)-1;
            if (date('F',strtotime($this->events[$ndx]['date']))==$curr){
                return false;
            } else {
                return true;
            }
        }
    }

    public function toggleStatus(){
        if ($this->status=="Full church calendar"){
            $this->status="My personal diary";
        } else {
            $this->status="Full church calendar";
        }
        $this->loadEvents();
    }

    public function render(){
        return view('church::livewire.calendar');
    }

    private function calendar_attend($description){
        if (str_contains($description,'group_id')){
            $id=substr($description,9);
            if ($id==0){
                return "yes";
            } else {
                $result=DB::table('group_individual')
                ->where('individual_id', $_COOKIE['wmc-id'])
                ->where('group_id',$id)
                ->get();
                if (count($result)==0){
                    return "no";
                } else {
                    return "yes";
                }
            }
        } else {
            return "no";
        }
    }
}