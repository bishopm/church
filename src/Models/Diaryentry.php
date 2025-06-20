<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Guava\Calendar\Contracts\Eventable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Bishopm\Church\Models\Venue;
use Bishopm\Church\Models\Agendaitem;
use Bishopm\Church\Models\Tenant;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Diaryentry extends Model implements Eventable
{
    public $table = 'diaryentries';
    protected $guarded = ['id'];

    public function toCalendarEvent(): CalendarEvent|array {
        $colour="#14B8A6";
        if ($this->details==''){
            if ($this->diarisable_type=="tenant"){
                if (isset($this->diarisable->tenant)){
                    $this->details=$this->diarisable->tenant;
                }
                if ($this->diarisable_id==setting('admin.church_tenant')){
                    $colour="blue";    
                }
            } elseif ($this->diarisable_type=="group"){
                $colour="blue";
                if (isset($this->diarisable->groupname)){
                    $this->details=$this->diarisable->groupname;
                }
            } elseif ($this->diarisable_type=="event"){
                $colour="red";
                if (isset($this->diarisable->event)){
                    $this->details=$this->diarisable->event;
                }    
            } elseif ($this->diarisable_type=="course"){
                $colour="green";
                if (isset($this->diarisable->course)){
                    $this->details=$this->diarisable->course;
                }    
            } elseif ($this->diarisable_type=="project"){
                $colour="orange";
                if (isset($this->diarisable->project)){
                    $this->details=$this->diarisable->project;
                }    
            }
        } else {
            if ($this->diarisable_type=="tenant"){
                if (isset($this->diarisable->tenant)){
                    $this->details=$this->diarisable->tenant . " (" . $this->details . ")";
                }
                if ($this->diarisable_id==setting('admin.church_tenant')){
                    $colour="blue";    
                }
            } elseif ($this->diarisable_type=="group"){
                $colour="blue";
                if (isset($this->diarisable->groupname)){
                    $this->details=$this->diarisable->groupname . " (" . $this->details . ")";
                }
            } elseif ($this->diarisable_type=="event"){
                $colour="red";
                if (isset($this->diarisable->event)){
                    $this->details=$this->diarisable->event . " (" . $this->details . ")";
                }
            } elseif ($this->diarisable_type=="course"){
                $colour="green";
                if (isset($this->diarisable->course)){
                    $this->details=$this->diarisable->course . " (" . $this->details . ")";
                }
            } elseif ($this->diarisable_type=="project"){
                $colour="orange";
                if (isset($this->diarisable->project)){
                    $this->details=$this->diarisable->project . " (" . $this->details . ")";
                }
            }
        }
        if (!$this->details){
            $this->details = "-";
        }

        $event = CalendarEvent::make($this)
            ->title($this->details)
            ->backgroundColor($colour)
            ->start($this->diarydatetime)
            ->end(date('Y-m-d',strtotime($this->diarydatetime)) . " " . $this->endtime)
            ->extendedProp('tenant', $this->diarisable_type);
        if ($this->venue_id) {
            $event->resourceId($this->venue_id);
        }
        return $event;        
    }

    public function diarisable(): MorphTo
    {
        return $this->morphTo();
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function agendaitems(): HasMany
    {
        return $this->hasMany(Agendaitem::class);
    }

}
