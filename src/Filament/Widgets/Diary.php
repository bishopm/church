<?php

namespace Bishopm\Church\Filament\Widgets;

use Bishopm\Church\Models\Event;
use Bishopm\Church\Models\Plan;
use Carbon\Carbon;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;


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
        $this->widgetdata['plans']=Plan::with('person')->whereIn('service_id',array_keys($this->widgetdata['services']))->where('servicedate','>=',$today)->where('servicedate','<=',$nextweek)->orderBy('servicedate','ASC')->get();
        $this->widgetdata['events']=[];
        $events=Event::where('eventdate','>=',$today)->where('eventdate','<=',$nextweek)->orderBy('eventdate','ASC')->get();
        foreach ($events as $event){
            $event->dayofweek=Carbon::parse($event->startdate)->format('D');
            $event->starttime=Carbon::parse($event->starttime)->format('H:i');
            $this->widgetdata['events'][]=$event;
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
