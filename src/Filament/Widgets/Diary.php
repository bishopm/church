<?php

namespace Bishopm\Church\Filament\Widgets;

use Bishopm\Church\Models\Plan;
use Carbon\Carbon;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;
use Spatie\GoogleCalendar\Event;

class Diary extends Widget
{
    protected static string $view = 'church::widgets.diary';

    public ?array $widgetdata;

    function mount() {
        $servicetimes=setting('general.services');
        foreach($servicetimes as $servicetime){
            $stimes[]=str_replace('h',':',$servicetime);
        }
        $this->widgetdata['services']=DB::connection('methodist')->table('services')->where('society_id',setting('services.society_id'))->whereIn('servicetime',$stimes)->pluck('servicetime','id')->toArray();
        $today = date('Y-m-d');
        $nextweek = date('Y-m-d',strtotime('+10 days'));
        $this->widgetdata['plans']=Plan::with('person')->whereIn('service_id',array_keys($this->widgetdata['services']))->where('servicedate','>=',$today)->where('servicedate','<=',$nextweek)->get();
        $events=Event::get(new Carbon($today),new Carbon(date('Y-m-d',strtotime('+1 month'))));
        foreach ($events as $event){
            if ($event->startDateTime){
                $this->widgetdata['events'][]=[
                    'name' => $event->name,
                    'date' => date('D j M',strtotime($event->startDateTime)),
                    'time' => date('H:i',strtotime($event->startDateTime))
                ];
            } else {
                $this->widgetdata['events'][]=[
                    'name' => $event->name,
                    'date' => date('D j M',strtotime($event->startDate)),
                    'time' => ''
                ];
            }
        }
    }

    public static function canView(): bool 
    { 
        $roles =auth()->user()->roles->toArray(); 
        $permitted = array('Office','Finance','Worship');
        foreach ($roles as $role){
            if ((in_array($role['name'],$permitted)) or (auth()->user()->isSuperAdmin())){
                return true;
            }
        }
        return false;
    }
}
