<?php
 
namespace Bishopm\Church\Livewire;

use Bishopm\Church\Models\Diaryentry;
use Bishopm\Church\Models\Individual;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Calendar extends Component
{
    public $events = [];
    public $status="public";
    public $me;
    public $mth,$first;
    public $loaded=false;
    public $headings;
 
    public function mount()
    {
        $this->headings['public']="Show personal calendar";
        $this->headings['my']="Show full calendar";
        $today=date('Y-m-d');
        $sixmonths=date('Y-m-d',strtotime('+ 6 months'));
        $this->events['public']=Diaryentry::with('diarisable')->where('diarydatetime','>',$today)->where('diarydatetime','<',$sixmonths)->where('calendar',1)->orderBy('diarydatetime','ASC')->get();
        $indiv=Individual::with('groups')->where('id',$_COOKIE['wmc-id'])->first();
        $mygroups=array();
        foreach ($indiv->groups as $group){
            $mygroups[]=$group->id;
        }
        $this->events['my']=Diaryentry::where('diarisable_type','group')->whereIn('diarisable_id',$mygroups)->where('diarydatetime','>',$today)->where('diarydatetime','<',$sixmonths)->orderBy('diarydatetime','ASC')->get();
        $this->first=false;
        $this->loaded = true;
    }

    public function toggleStatus(){
        if ($this->status=="public"){
            $this->status="my";
        } else {
            $this->status="public";
        }
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