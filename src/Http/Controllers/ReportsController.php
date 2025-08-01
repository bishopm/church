<?php

namespace Bishopm\Church\Http\Controllers;

use Bishopm\Church\Http\Controllers\Controller;
use Bishopm\Church\Models\Chord;
use Bishopm\Church\Models\Diaryentry;
use Bishopm\Church\Models\Event;
use Bishopm\Church\Models\Group;
use Bishopm\Church\Models\Individual;
use Bishopm\Church\Models\Meeting;
use Illuminate\Support\Str;
use Bishopm\Church\Models\Song;
use Bishopm\Church\Models\Roster;
use Bishopm\Church\Models\Service;
use Bishopm\Church\Models\Rostergroup;
use Bishopm\Church\Models\Rosteritem;
use Bishopm\Church\Models\Series;
use Bishopm\Church\Models\Venue;
use Bishopm\Church\Classes\tFPDF;
use Bishopm\Church\Models\Form;
use Bishopm\Church\Models\Gift;
use Bishopm\Church\Models\Midweek;
use Bishopm\Church\Models\Plan;
use Bishopm\Church\Models\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use stdClass;

class ReportsController extends Controller
{
    public $pdf, $title, $subtitle, $page, $logo, $widelogo;

    public function __construct(){
        $this->pdf = new tFPDF();
        $this->pdf->AddFont('DejaVu','','DejaVuSans.ttf',true);
        $this->pdf->AddFont('DejaVu', 'B', 'DejaVuSans-Bold.ttf', true);
        $this->pdf->AddFont('DejaVu', 'I', 'DejaVuSans-Oblique.ttf', true);
        $this->pdf->AddFont('DejaVuCond','','DejaVuSansCondensed.ttf',true);
        $this->pdf->AddFont('DejaVuCond', 'B', 'DejaVuSansCondensed-Bold.ttf', true);
        $this->title="";
        $this->subtitle="";
        $this->page=0;
        $this->logo=url('/') . "/church/images/blacklogo.png";
        $this->widelogo=url('/') . "/church/images/bwidelogo.png";
    }

    public function a4meeting ($recordId){
        $mtg=Meeting::with(['agendaitems' => function($q) { $q->orderBy('sortorder', 'asc'); }])->where('id',$recordId)->first();
        $this->pdf->AddPage('P');
        $this->title=date("j F Y H:i",strtotime($mtg->meetingdatetime));
        $this->pdf->SetTitle($this->title);
        $this->pdf->SetAutoPageBreak(true, 0);
        $this->pdf->SetFont('DejaVu', 'B', 18);
        $this->pdf->Image($this->widelogo,123,8,77,30);
        $this->pdf->text(20, 16, $mtg->details);
        $this->pdf->SetFont('DejaVu', '', 14);
        $this->pdf->text(20, 23, $this->title);
        $this->pdf->SetFont('DejaVu', 'B', 14);
        $this->pdf->text(20, 32, "Agenda");
        $this->pdf->line(20, 35, 195, 35);
        $items=$mtg->agendaitems;
        $yy=44;
        $ndx=0;
        foreach ($items as $item){
            if ($item->level==1){
                $this->pdf->SetFont('DejaVu', 'B', 12);
                $ndx=intval(floor($ndx)+1);
                $this->pdf->text(20, $yy, $ndx . "  " . $item->heading);
            } else {
                $yy=$yy-2;
                $this->pdf->SetFont('DejaVu', '', 11);
                $ndx=$ndx+0.1;
                $this->pdf->text(25, $yy, $ndx . "  " . $item->heading);
            }
            $yy=$yy+8;
        }
        if ($mtg->nextmeeting){
            $this->pdf->SetFont('DejaVu', 'B', 12);
            $this->pdf->text(20, $yy, "Next meeting:  " . date('D j M Y H:i', strtotime($mtg->nextmeeting)));
        }
        $filename=$this->title;
        $this->pdf->Output('I',$filename);
        exit;
    }

    public function a5meeting ($recordId){
        $mtg=Meeting::with(['agendaitems' => function($q) { $q->orderBy('sortorder', 'asc'); }])->where('id',$recordId)->first();
        $this->pdf->AddPage('L');
        $xadd=147;
        $this->title=date("j F Y H:i",strtotime($mtg->meetingdatetime));
        $this->pdf->SetTitle($this->title);
        $this->pdf->SetAutoPageBreak(true, 0);
        $this->pdf->SetFont('DejaVu', 'B', 15);
        $this->pdf->Image($this->widelogo,85,8,60);
        $this->pdf->Image($this->widelogo,85+$xadd,8,60);
        $this->pdf->text(10, 11, $mtg->details);
        $this->pdf->text(10+$xadd, 11, $mtg->details);
        $this->pdf->SetFont('DejaVu', '', 12);
        $this->pdf->text(10, 18, $this->title);
        $this->pdf->text(10+$xadd, 18, $this->title);
        $this->pdf->SetFont('DejaVu', 'B', 12);
        $this->pdf->text(10, 27, "Agenda");
        $this->pdf->text(10+$xadd, 27, "Agenda");
        $this->pdf->line(10, 30, 142, 30);
        $this->pdf->line($xadd+10, 30, $xadd+142, 30);
        $items=$mtg->agendaitems;
        $yy=39;
        $ndx=0;
        foreach ($items as $item){
            if ($item->level==1){
                $this->pdf->SetFont('DejaVu', 'B', 11);
                $ndx=intval(floor($ndx)+1);
                $this->pdf->text(10, $yy, $ndx . "  " . $item->heading);
                $this->pdf->text($xadd+10, $yy, $ndx . "  " . $item->heading);
            } else {
                $yy=$yy-2;
                $this->pdf->SetFont('DejaVu', '', 10);
                $ndx=$ndx+0.1;
                $this->pdf->text(15, $yy, $ndx . "  " . $item->heading);
                $this->pdf->text($xadd+15, $yy, $ndx . "  " . $item->heading);
            }
            $yy=$yy+7;
        }
        if ($mtg->nextmeeting){
            $this->pdf->SetFont('DejaVu', 'B', 11);
            $this->pdf->text(10, $yy, "Next meeting:  " . date('D j M Y H:i', strtotime($mtg->nextmeeting)));
            $this->pdf->text($xadd+10, $yy, "Next meeting:  " . date('D j M Y H:i', strtotime($mtg->nextmeeting)));
        }
        $filename=$this->title;
        $this->pdf->Output('I',$filename);
        exit;
    }

    private function addRoster($label,$servicetime,$servicedate){
        $group=Group::where('groupname',$label)->first();
        if ($group){
            $group_id=$group->id;
            $rostergroups=Rostergroup::with('roster')->where('group_id',$group_id)->get();
            foreach ($rostergroups as $rg){
                if (str_contains($rg->roster->roster,$servicetime)){
                    $rosteritem=Rosteritem::with('individuals')->where('rostergroup_id',$rg->id)->where('rosterdate',$servicedate)->first();
                }
            }
            if ((isset($rosteritem)) and ($rosteritem->individuals)){
                $indivs=array();
                foreach ($rosteritem->individuals as $ind){
                    $indivs[]=$ind->firstname . " " . $ind->surname;
                }
                if ($label=="Society Stewards"){
                    $label = "Society Steward: " . implode(", ", $indivs);
                } elseif ($label=="Readers") {
                    $label = implode(", ", $indivs);
                } else {
                    $label = $label . ": " . implode(", ", $indivs);
                }
            } else {
                if ($label=="Bible reading"){
                    $label = "";
                }
            }
        }
        return $label;
    }

    public function allvenues($reportdate=""){
        if (!$reportdate){
            $reportdate=date('Y-m-d');
        }
        $hours=['07h00','08h00','09h00','10h00','11h00','12h00','13h00','14h00','15h00','16h00','17h00','18h00','19h00','20h00','21h00','22h00'];
        $venues=Venue::where('resource',1)->orderBy('venue')->get();
        $this->title = "Venue Bookings: " . date('j F Y',strtotime($reportdate));
        $this->pdf = new tFPDF();
        $this->pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
        $this->pdf->SetFillColor(190,190,190);
        $this->pdf->AddPage('L');
        $this->pdf->SetTitle($this->title);
        $this->pdf->SetAutoPageBreak(true, 0);
        $this->pdf->SetFont('Arial', 'B', 22);
        $this->pdf->Image($this->logo,10,0,25,25);
        $this->pdf->text(40, 12, setting('general.church_name'));
        $this->pdf->SetFont('Arial', '', 16);
        $this->pdf->text(40, 20, $this->title);
        $this->pdf->SetFont('Arial', 'B', 12);
        $yy=40;
        $xx=35;
        $col=floor(254/count($venues));
        $width=$col*count($venues)+$xx-6;
        foreach ($hours as $hh){
            if ($hh<>$hours[count($hours)-1]){
                $this->pdf->line(10,$yy-6,$width,$yy-6);
                $this->pdf->text(13,$yy+1,$hh);
                $yy=$yy+11;
            }
        }
        $this->pdf->line(10,34,10,199);
        $this->pdf->line($width,34,$width,199);
        $this->pdf->line(10,$yy-6,$width,$yy-6);
        foreach ($venues as $venue){
            $bookings=Diaryentry::with('diarisable')->where(DB::raw('substr(diarydatetime, 1, 10)'),$reportdate)->where('venue_id',$venue->id)->get();
            $this->pdf->SetFont('Arial', '', 10);
            $this->pdf->setxy($xx-6,30);
            $this->pdf->cell($col,0,$venue->venue,0,0,'C');
            foreach ($bookings as $booking){
                $start=substr($booking->diarydatetime,11,5);
                $sh=substr($start,0,2);
                $sm=substr($start,3,2);
                $sy=array_search($sh."h00",$hours) * 11 + 40 + intval($sm)/60 * 11 - 6;
                $end=$booking->endtime;
                $eh=substr($end,0,2);
                $em=substr($end,3,2);
                $ey=array_search($eh."h00",$hours) * 11 + 40 + intval($em)/60 * 11 - 6;
                $this->pdf->rect($xx-6,$sy,$col,$ey-$sy,'DF');
                $this->pdf->setxy($xx-6,$sy+1);
                $this->pdf->SetFont('Arial', '', 8);
                if (($booking->diarisable_id) and (isset($booking->diarisable->tenant))){
                    $msg=$booking->diarisable->tenant;
                    if ($booking->details){
                        $msg.=" (" . $booking->details . ")";
                    }
                    $this->pdf->multicell($col,3,$msg,0,'C');
                } elseif (($booking->diarisable_id) and (isset($booking->diarisable->groupname))){
                    $msg=$booking->diarisable->groupname;
                    if ($booking->details){
                        $msg.=" (" . $booking->details . ")";
                    }
                    $this->pdf->multicell($col,3,$msg,0,'C');
                } elseif (($booking->diarisable_id) and (isset($booking->diarisable->event))){
                    $msg=$booking->diarisable->event;
                    if ($booking->details){
                        $msg.=" (" . $booking->details . ")";
                    }
                    $this->pdf->multicell($col,3,$msg,0,'C');
                }
                $this->pdf->SetFont('Arial', 'B', 12);
            }
            $this->pdf->line($xx-6,34,$xx-6,199);
            $xx=$xx+$col;
        }
        $this->pdf->line($xx-6,34,$xx-6,199);
        $this->pdf->Output();
        exit;
    }

    public function barcodes($newonly=""){
        if ($newonly){
            $individuals=array();
            if ($newonly=="new"){
                $sunday=date('Y-m-d',strtotime('last sunday'));
                $newindivs=Individual::where('created_at','>',$sunday)->get();               
                foreach ($newindivs as $newindiv){
                    $individuals[$newindiv->id]=$newindiv;
                }
            } else {
                $individuals[$newonly]=Individual::find($newonly);
            }
            $long=false;
        } else {
            $individuals=Individual::orderBy('surname','ASC')->orderBy('firstname','ASC')->get()->toArray();
            $long=true;
        }
        $this->pdf->AddPage('P');
        $this->pdf->SetFont('Arial', 'B', 11);
        
        $yy=10;
        $xx=10;
        if (count($individuals)){
            foreach ($individuals as $indiv){
                if ($yy>130){
                    $this->pdf->AddPage('P');
                    $yy=10;
                }
                $this->pdf->rect($xx,$yy,93,114);
                $this->pdf->Image($this->logo,$xx+70,$yy+57.5,20,20);
                $this->pdf->setxy($xx+4,$yy+24);
                $font=60;
                $size="unknown";
                do {
                    $this->pdf->SetFont('Arial', 'B', $font);
                    if ($indiv['firstname']){
                        $width=$this->pdf->GetStringWidth($indiv['firstname']);
                    } else {
                        $width=0;
                    }
                    if ($width < 85){
                        $this->pdf->cell(86,0,$indiv['firstname'],0,0,'C');
                        $size="known";
                        $font=8;
                    } else {
                        $font=$font-0.5;
                    }
                } while ($size=="unknown");
                $this->pdf->setxy($xx+4,$yy+40);
                $font=25;
                $size="unknown";
                do {
                    $this->pdf->SetFont('Arial', '', $font);
                    $width=$this->pdf->GetStringWidth($indiv['surname']);
                    if ($width < 85){
                        $this->pdf->cell(86,0,$indiv['surname'],0,0,'C');
                        $size="known";
                        $font=8;
                    } else {
                        $font=$font-0.5;
                    }
                } while ($size=="unknown");
                $this->pdf->SetFont('Arial', 'B', 11);
                $this->pdf->SetDrawColor(185,185,185);
                $this->pdf->line($xx,$yy+57,$xx+93,$yy+57);
                $this->pdf->SetDrawColor(0,0,0);
                $this->pdf->Code39($xx+5,$yy+60,$indiv['id'],1.5,10);
                $this->pdf->setxy($xx+4,$yy+72);
                $this->pdf->cell(100,0,$indiv['firstname'] . " " . $indiv['surname'],0,0,'L');
                if ($xx==10){
                    $xx=110;    
                } else {
                    $xx=10;
                    $yy=$yy+120;
                }
            }
            if ($long){
                $this->pdf->AddPage('P');
                $this->pdf->rect(10,10,93,114);
                $this->pdf->RoundedRect(15,15,83,47,2);
                $this->pdf->text(15,73,"First name and surname");
                $this->pdf->RoundedRect(15,74,83,12,1);
                $this->pdf->text(15,91,"Cellphone");
                $this->pdf->RoundedRect(15,92,83,12,1);
                $this->pdf->text(15,108,"Email");
                $this->pdf->RoundedRect(15,109,83,12,1);
                
                $this->pdf->rect(110,10,93,114);
                $this->pdf->RoundedRect(115,15,83,47,2);
                $this->pdf->text(115,73,"First name and surname");
                $this->pdf->RoundedRect(115,74,83,12,1);
                $this->pdf->text(115,91,"Cellphone");
                $this->pdf->RoundedRect(115,92,83,12,1);
                $this->pdf->text(115,108,"Email");
                $this->pdf->RoundedRect(115,109,83,12,1);
                
                $this->pdf->rect(10,130,93,114);
                $this->pdf->RoundedRect(15,135,83,47,2);
                $this->pdf->text(15,193,"First name and surname");
                $this->pdf->RoundedRect(15,194,83,12,1);
                $this->pdf->text(15,211,"Cellphone");
                $this->pdf->RoundedRect(15,212,83,12,1);
                $this->pdf->text(15,228,"Email");
                $this->pdf->RoundedRect(15,229,83,12,1);
                
                $this->pdf->rect(110,130,93,114);
                $this->pdf->RoundedRect(115,135,83,47,2);
                $this->pdf->text(115,193,"First name and surname");
                $this->pdf->RoundedRect(115,194,83,12,1);
                $this->pdf->text(115,211,"Cellphone");
                $this->pdf->RoundedRect(115,212,83,12,1);
                $this->pdf->text(115,228,"Email");
                $this->pdf->RoundedRect(115,229,83,12,1);
                
                $this->pdf->SetFont('Arial', 'B', 11);
                $this->pdf->SetDrawColor(185,185,185);
                $this->pdf->line(10,67,103,67);
                $this->pdf->line(110,67,203,67);
                $this->pdf->line(10,187,103,187);
                $this->pdf->line(110,187,203,187);
                $this->pdf->SetDrawColor(0,0,0);
            }
        } else {
            $this->pdf->text(10,10,"No new members have been added to the system since last Sunday");
        }
        $this->pdf->Output('I',"Name tag");
        exit;
    }

    public function calendar($yr=""){
        if (!$yr){
            $yr=date('Y');
        }
        $meetings=Meeting::with('venue')->where('meetingdatetime','>=',$yr . '-01-01')->where('meetingdatetime','<=',$yr . '-12-31')->orderBy('meetingdatetime','ASC')->where('calendar',1)->get();
        $events=Event::with('venue')->where('eventdate','>=',$yr . '-01-01')->where('eventdate','<=',$yr . '-12-31')->orderBy('eventdate','ASC')->where('calendar',1)->get();
        $courses=[];//Course::with('venue')->where('coursedate','>=',$yr . '-01-01')->where('coursedate','<=',$yr . '-12-31')->orderBy('coursedate','ASC')->where('calendar',1)->get();
        $bookings=Diaryentry::with('venue')->where('calendar',1)->where('diarydatetime','>=',$yr . '-01-01')->where('diarydatetime','<=',$yr . '-12-31')->orderBy('diarydatetime','ASC')->get();
        $dates=array();
        foreach ($bookings as $booking){
            $dates[strtotime($booking->diarydatetime)][]=[
                'datetime'=> $booking->diarydatetime,
                'details'=>$booking->details,
                'venue'=>$booking->venue->venue
            ];
        }
        foreach ($events as $event){
            $dates[strtotime($event->eventdate)][]=[
                'datetime'=> $event->eventdate,
                'details'=>$event->event,
                'venue'=>$event->venue->venue
            ];
        }
        foreach ($courses as $course){
            $dates[strtotime($course->coursedate)][]=[
                'datetime'=> $course->coursedate,
                'details'=>$course->course,
                'venue'=>$course->venue->venue
            ];
        }
        foreach ($meetings as $meeting){
            $dates[strtotime($meeting->meetingdatetime)][]=[
                'datetime'=> $meeting->meetingdatetime,
                'details'=>$meeting->details,
                'venue'=>$meeting->venue->venue
            ];
        }
        $this->title = $yr . " Calendar";
        $this->pdf->SetTitle($this->title);
        $this->pdf->AddPage('P');
        $this->pdf->SetAutoPageBreak(true, 0);
        $this->pdf->SetFont('DejaVuCond', 'B', 22);
        $this->pdf->Image($this->logo,10,5,25,25);
        $this->pdf->text(40, 17, setting('general.church_name'));
        $this->pdf->SetFont('DejaVuCond', '', 16);
        $this->pdf->text(40, 25, $this->title);
        $this->pdf->SetFont('DejaVuCond', 'B', 14);
        $this->pdf->line(10, 29, 200, 29);
        $this->pdf->SetFont('DejaVuCond', '', 12);
        $y=35;
        ksort($dates);
        $month="";
        foreach ($dates as $day){
            foreach ($day as $date){
                if ($month<>date('F',strtotime($date['datetime']))){
                    $month=date('F',strtotime($date['datetime']));
                    $this->pdf->SetFont('DejaVuCond', 'B', 12);
                    $this->pdf->text(10,$y+2,$month);
                    $this->pdf->SetFont('DejaVuCond', '', 12);
                    $y=$y+6.5;
                }
                $this->pdf->text(10,$y,date('d M (D)',strtotime($date['datetime'])));
                $this->pdf->text(37,$y,date('H:i',strtotime($date['datetime'])));
                $this->pdf->text(52,$y,$date['details']);
                $this->pdf->text(150,$y,$date['venue']);
                $y=$y+4.5;
                if ($y > 280){
                    $this->pdf->AddPage('P');
                    $this->pdf->SetFont('DejaVuCond', 'B', 22);
                    $this->pdf-> Image($this->logo,10,0,25,25);
                    $this->pdf->text(40, 12, setting('general.church_name'));
                    $this->pdf->SetFont('DejaVuCond', '', 16);
                    $this->pdf->text(40, 20, $this->title);
                    $this->pdf->SetFont('DejaVuCond', 'B', 14);
                    $this->pdf->line(10, 24, 200, 24);
                    $this->pdf->SetFont('DejaVuCond', '', 12);
                    $y=32;  
                }
            }
        }
        $this->pdf->Output('I','Calendar');
        exit;
    }

    public function convert_smart_quotes($string) {
        $search = array(chr(0xe2) . chr(0x80) . chr(0x98),
                        chr(0xe2) . chr(0x80) . chr(0x99),
                        chr(0xe2) . chr(0x80) . chr(0x9c),
                        chr(0xe2) . chr(0x80) . chr(0x9d),
                        chr(0xe2) . chr(0x80) . chr(0x93),
                        chr(0xe2) . chr(0x80) . chr(0x94),
                        chr(226) . chr(128) . chr(153),
                        'â€™','â€œ','â€<9d>','â€"','Â  ');
        $replace = array("'","'",'"','"',' - ',' - ',"'","'",'"','"',' - ',' ');
    
        return str_replace($search, $replace, $string);
    }

    public function form($id)
    {
        $form = Form::with('formitems')->where('id',$id)->first();
        $widths=array(
            'full'=>1,
            'half'=>2,
            'third'=>3,
            'quarter'=>4
        );
        $i=$widths[$form->width];
        if ($form->orientation=='portrait'){
            $this->pdf->AddPage('P');
            $xadd=210/$i;
        } else {
            $this->pdf->AddPage('L');
            $xadd=297/$i;
        }
        $this->pdf->SetTitle($form->name);
        $this->pdf->SetAutoPageBreak(true, 0);
        $this->pdf->SetFont('Arial', 'B', 12);
        // A4 is 210 x 297mm
        for ($j=0;$j<$i;$j++){
            foreach ($form->formitems as $item){
                $props=json_decode($item->itemdata);
                if ($item->itemtype=="text"){
                    $this->pdf->SetFont($props->font,$props->fontstyle,$props->fontsize);
                    $this->pdf->text($props->x+($j*$xadd),$props->y,$props->text);
                } elseif ($item->itemtype=="cell"){
                    $this->pdf->SetFont($props->font,$props->fontstyle,$props->fontsize);
                    $this->pdf->setxy($props->x+($j*$xadd),$props->y);
                    if ($props->rounded > 0){
                        $this->pdf->RoundedRect($props->x+($j*$xadd),$props->y,$props->width,$props->height,$props->rounded);
                        $this->pdf->cell($props->width,$props->height,$props->text,0,0,$props->alignment,0);
                    } else {
                        $this->pdf->cell($props->width,$props->height,$props->text,$props->border,0,$props->alignment,$props->fill);
                    }
                } elseif ($item->itemtype=="line"){
                    $this->pdf->line($props->x+($j*$xadd),$props->y,$props->x2+($j*$xadd),$props->y2);
                } elseif ($item->itemtype=="image"){
                    $this->pdf->image(url('/') . "/church/images/" . $props->file,$props->x+($j*$xadd),$props->y,$props->width,$props->height);
                }
            }
        }
        $filename=Str::slug($form->name, "-");
        $this->pdf->Output('I',$filename);
        exit;
    }

    private function GetExtraInfo($setitem){
        if ($setitem->setitemable_type=="song"){
            $song = Song::find($setitem->setitemable_id);
            if ($song->musictype=="hymn"){
                if ($song->tune){
                    return "Tune: " . $song->tune . " " . $song->verseorder;
                } else {
                    return $song->verseorder;
                }
            } else {
                return $song->firstline;
            }
        } elseif (!isset($setitem->setitemable_id)) {
            $set=Service::with('series')->where('id',$setitem->service_id)->first();
            if ($setitem->note=="Bible reading"){
                return $set->reading . " (" . $this->addRoster("Readers",$set->servicetime,$set->servicedate) . ")";
            } elseif ($setitem->note=="Sermon"){
                if ($set->person){
                    $extra=$set->person->firstname . " " . $set->person->surname;
                } else {
                    $url="https://methodist.church.net.za/preacher/" . setting('services.society_id') . "/" . $set->servicetime . "/" . substr($set->servicedate,0,10);
                    $response=Http::get($url);
                    $extra = $response->body();
                }
                if ((isset($set->series)) and ($set->series->series !== "")) {
                    $extra = $extra . " (" . $set->series->series . ")";
                }
                return $extra;
            }
        }
    }

    private function getRosterWeeks($roster,$firstday){
        $weeks[]=$firstday;
        $ym=date('Y-m',strtotime($firstday));
        $nm=date('Y-m',strtotime($firstday . ' + 1 month'));
        for ($i=1;$i<5;$i++){
            if ($ym== date('Y-m',strtotime($firstday . ' + ' . $i * 7 . ' days'))){
                $weeks[]=date('Y-m-d',strtotime($firstday . ' + ' . $i * 7 . ' days'));
            }
        }

        // Deal with midweek services and check if potential services have preachers before adding to this roster
        $mws=array();
        $roster = Roster::find($roster);
        $servicetime=str_replace("h",":",$roster->sundayservice);
        $service=DB::connection('methodist')->table('services')->where('society_id',setting('services.society_id'))->where('servicetime',$servicetime)->first();
        $midweeks=Midweek::where('servicedate','>=',$firstday)->where('servicedate','<',date('Y-m-d',strtotime($firstday . ' + 1 month')))->get();
        $mws=array();
        if (count($midweeks)){
            foreach ($midweeks as $mw){
                if ($service){
                    $plan=Plan::where('servicedate',$mw->servicedate)->where('service_id',$service->id)->get();
                    if (count($plan)){
                        $weeks[]=$mw->servicedate;
                    }
                    $mws[$mw->servicedate]=$mw->midweek;
                }
            }
        }
        asort($weeks);
        $dum=[
            'columns' => array_values($weeks),
            'midweeks' => $mws
        ];
        return $dum;
    }

    private function getRosterData($today,$id) {
        $firstday=date('l',strtotime($today.'-01'));
        $alldays=['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
        $rostermodel=Roster::find($id);
        $dday = 8 - array_search($firstday,$alldays) + array_search($rostermodel->dayofweek,$alldays);
        if ($dday > 7){
            $dday=$dday-7;
        }
        $firstdate=$today . '-0' . $dday;
        $data = array();
        $rosterweeks=$this->getRosterWeeks($id,$firstdate);
        $data['columns']=$rosterweeks['columns'];
        $data['midweeks']=$rosterweeks['midweeks'];
        $groups = DB::table('rosters')->join('rostergroups', 'rosters.id', '=', 'rostergroups.roster_id')
            ->join('groups', 'rostergroups.group_id', '=', 'groups.id')
            ->select('groupname','groups.id','rostergroups.extrainfo')
            ->where('rosters.id',$id)
            ->orderBy('groupname')
            ->get();
        if ((isset($rostermodel->sundayservice)) and ($rostermodel->sundayservice!=='')){
            $preachergroup=new stdClass();
            $preachergroup->groupname="Preacher";
            $preachergroup->id=0;
            $preachergroup->extrainfo="";
            $groups[] = $preachergroup;
        }
        foreach ($groups as $group){
            $data['rows'][$group->groupname]['id']=$group->id;
            if ($group->extrainfo=='yes'){
                $data['rows'][$group->groupname]['extra']='yes';
            } 
            foreach ($data['columns'] as $col){
                $fixdate=date('Y-m-d',strtotime($col));
                if ($group->id==0){
                    $url="https://methodist.church.net.za/preacher/" . setting('services.society_id') . "/" . $rostermodel->sundayservice . "/" . date('Y-m-d',strtotime($col));
                    $response=Http::get($url);
                    $extra = $response->body();
                    if ((isset($set->series)) and ($set->series->series !== "")) {
                        $extra = $extra . " (" . $set->series->series . ")";
                    }
                    $data['rows'][$group->groupname][$col][] = $extra;
                } else {
                    $dum=DB::table('rosteritems')->join('rostergroups','rosteritems.rostergroup_id','=','rostergroups.id')
                        ->join('rosters','rostergroups.roster_id','=','rosters.id')
                        ->join('groups','rostergroups.group_id','=','groups.id')
                        ->join('individual_rosteritem','individual_rosteritem.rosteritem_id','rosteritems.id')
                        ->select('individual_rosteritem.individual_id')
                        ->where('rosteritems.rosterdate','=',$fixdate)
                        ->where('groups.id',$group->id)
                        ->where('rosters.id','=',$id)
                        ->get();
                    if (count($dum)){
                        foreach ($dum as $individ) {
                            if ($individ->individual_id < 0){
                                $indivextra=Rostergroup::where('roster_id',$id)->where('group_id',$group->id)->first()->extraoptions;
                                $eoptions=explode(",",$indivextra);
                                foreach ($eoptions as $ko=>$eo){
                                    if ($individ->individual_id == -1 * (1+$ko)){
                                        $indivdata=$eo;
                                    }
                                }
                            } else {
                                $indiv=Individual::find($individ->individual_id);
                                if ($indiv){
                                    $indivdata=$indiv->surname . ', ' . $indiv->firstname;
                                }
                            }
                            if ($indiv){
                                $data['rows'][$group->groupname][$col][$indiv->id] = $indivdata;
                            } else {
                                $data['rows'][$group->groupname][$col][] = "-";
                            }
                        }
                    } else {
                        $data['rows'][$group->groupname][$col][] = "-";
                    }
                    unset($dum);
                }
            }
        }
        return $data;
    }

    public function group($id)
    {
        $group = Group::with('individuals')->find($id);
        $this->pdf->AddPage('P');
        $this->pdf->SetAutoPageBreak(true, 0);
        $this->pdf->SetFont('DejaVu', 'B', 18);
        $this->pdf->text(15, 16, setting('general.church_name'));
        $this->pdf->SetFont('DejaVu', '', 16);
        if ($group->individual){
            $this->pdf->text(15, 23, $group->groupname . " (" . $group->individual->firstname . " " . $group->individual->surname . ")");
        } else {
            $this->pdf->text(15, 23, $group->groupname);
        }
        $this->pdf->SetTitle($group->groupname);
        $this->pdf->SetFont('DejaVu', '', 12);
        $this->pdf->text(170, 23, date('Y-m-d'));
        $this->pdf->line(15, 26, 195, 26);
        $yy=32;
        $indivs = array();
        foreach ($group->individuals as $indiv) {
            $cc=$indiv->cellphone;
            if ($indiv->pivot->categories){
                foreach (json_decode($indiv->pivot->categories) as $cat){
                    $indivs[$cat][$indiv->firstname . $indiv->surname]=$indiv;
                }
            } else {
                $indivs['other'][]=$indiv;
            }
        }
        ksort($indivs);
        foreach ($indivs as $k=>$category){
            if (is_array($indivs[$k])){
                ksort($indivs[$k]);
            }
        }
        foreach ($indivs as $cc=>$cats){
            if ($cc <>"other"){
                $yy=$yy+2;
                $this->pdf->SetFont('DejaVu', 'B', 12);
                $this->pdf->text(15, $yy, $cc);
                $this->pdf->SetFont('DejaVu', '', 12);
                $yy=$yy+6;
            }
            foreach ($cats as $kk=>$ii) {
                if ($yy>285){
                    $yy=35;
                    $this->pdf->AddPage('P');
                    $this->pdf->SetFont('DejaVu', 'B', 18);
                    $this->pdf->text(15, 16, setting('general.church_name'));
                    $this->pdf->SetFont('DejaVu', '', 16);
                    $this->pdf->text(15, 23, $group->groupname);
                    $this->pdf->SetTitle($group->groupname);
                    $this->pdf->SetFont('DejaVu', '', 12);
                    $this->pdf->text(170, 23, date('Y-m-d'));
                    $this->pdf->line(15, 26, 195, 26);
                }
                $this->pdf->text(15, $yy, $ii->firstname . " " . $ii->surname);
                $this->pdf->text(169, $yy, $ii->cellphone);
                $yy=$yy+6;
            }
        }
        $this->pdf->Output();
        exit;
    }

    public function meetings($id){
        $data['group']=Group::with(['meetings' => function ($q) { $q->orderBy('meetingdatetime', 'desc'); }])->where('id',$id)->first();
        return view('church::web.meetings',$data);
    }

    public function minutes($id, $email="") {
        $meeting=Meeting::with('group','agendaitems')->with(['agendaitems.tasks' => fn($q) => $q
            ->where('statusnote','<>','')->orWhereNull('deleted_at')->withTrashed()])->where('id',$id)->first();
        if (isset($meeting->group)){
            $this->title = $meeting->group->groupname  . " minutes";
        } else {
            $this->title = $meeting->details  . " minutes";
        }     
        $this->pdf->SetTitle($this->title);
        $page=0;
        $this->subtitle='Meeting held on ' .  date('j F Y',strtotime($meeting->meetingdatetime)) . " (" . $meeting->venue->venue . ")";
        $this->pdf=$this->report_header();
        if ((isset($meeting->group)) and (isset($meeting->attendance))){   
            $y=33;
            $attendees=Individual::whereIn('id',$meeting->attendance)->orderBy('firstname')->get();
            $present = "Present: ";
            foreach ($attendees as $ndx=>$indiv){
                if ($ndx>0){
                    $present.=", ";    
                } 
                $present.=$indiv->firstname . " " . $indiv->surname;
            }
            $this->pdf->setxy(9,$y);
            $this->pdf->MultiCell(0,5,$present);
            $y=$this->pdf->getY()+7;
        } else {
            $y=40;
        }
        $count=1;
        foreach ($meeting->agendaitems as $agenda){
            $sub=1;
            if ((isset($agenda->minute)) or (count($agenda->tasks))){
                if ($y>255){
                    $this->pdf=$this->report_header();
                    $page++;
                    $y=35;
                }
                $this->pdf->SetFont('DejaVu', 'B', 14);
                $this->pdf->text(10,$y,$count . ".");
                $this->pdf->text(20,$y,$agenda->heading);
                $this->pdf->SetFont('DejaVu', '', 11);
                $y=$y+5;
                if ($agenda->minute){
                    $this->pdf->setxy(19,$y-4);
                    $this->pdf->MultiCell(181,4.5,$agenda->minute,0,'J');
                    $y=$this->pdf->getY()+4;
                }
                foreach ($agenda->tasks as $task){
                    if ($y>255){
                        $this->pdf=$this->report_header();
                        $page++;
                        $y=35;
                    }
                    $this->pdf->setxy(155,$y-1);
                    $this->pdf->SetFont('DejaVu', 'B', 9);
                    if ($task->duedate){
                        if ($task->statusnote){
                            $this->pdf->SetFont('DejaVu', '', 9);
                            $this->pdf->SetTextColor(125,125,125);
                        } 
                        $this->pdf->cell(0,0,date('j M',strtotime($task->duedate)));
                        $this->pdf->SetTextColor(0,0,0);
                        $this->pdf->SetFont('DejaVu', 'B', 9);
                    }
                    $this->pdf->setxy(168,$y-1);
                    if ($task->individual_id){
                        $indiv=Individual::find($task->individual_id);
                        if ($task->statusnote){
                            $this->pdf->SetFont('DejaVu', '', 9);
                            $this->pdf->SetTextColor(125,125,125);
                        } 
                        $this->pdf->cell(0,0,$indiv->firstname . " " . $indiv->surname);
                        $this->pdf->SetTextColor(0,0,0);
                    }
                    $this->pdf->SetFont('DejaVu', '', 11);
                    $this->pdf->text(10,$y,$count . "." . $sub);
                    $this->pdf->setxy(19,$y-3.5);
                    $this->pdf->MultiCell(135,4.5,$task->description);
                    $y=$this->pdf->GetY()+3;
                    if ($task->statusnote){
                        $this->pdf->setxy(19,$y-2.5);
                        $this->pdf->SetFont('DejaVu', 'I', 11);
                        $this->pdf->MultiCell(135,4.5,'[' . $task->statusnote . ']');
                        $y=$this->pdf->GetY()+3;
                    }
                    $sub++;
                }
                $count++;
                $y=$y+3;
            }
        }
        $this->pdf->SetFont('DejaVu', '', 11);
        if ($y>255){
            $this->pdf=$this->report_header();
            $page++;
            $y=35;
        } else {
            if ($y>40){
                $y=$this->pdf->GetY()+5;
            }
        }
        if ($meeting->nextmeeting){
            $this->pdf->text(10,$y+15,"Next meeting: " . date('j F Y (H:i)',strtotime($meeting->nextmeeting)));
            $this->pdf->text(10,$y+5,"Signed on " . date('j M Y',strtotime($meeting->nextmeeting)). " as a true record of the decisions taken at this meeting");
        } else {
            $this->pdf->text(10,$y+5,"Signed on                           as a true record of the decisions taken at this meeting");
        }
        $this->pdf->rect(8,$y-2,194,12);
        if (!$email){
            $this->pdf->Output();
        } else {
            return $this->pdf->Output('S');
        }
    }

    private function report_header(){
        if (!isset($this->page)){
            $this->page=0;
        }
        $this->page++;
        $this->pdf->AddPage('P');
        $this->pdf->SetAutoPageBreak(true, 0);
        $this->pdf->SetFont('DejaVu', 'B', 22);
        $this->pdf->Image($this->logo,10,5,25,25);
        $this->pdf->text(40, 15, setting('general.church_name'));
        $this->pdf->line(10, 29, 200, 29);
        if ($this->subtitle==""){
            $this->pdf->SetFont('DejaVu', '', 18);
            $this->pdf->text(40, 23, $this->title);
        } else {
            $this->pdf->SetFont('DejaVu', '', 16);
            $this->pdf->text(40, 21, $this->title);
            $this->pdf->SetFont('DejaVu', '', 11);
            $this->pdf->text(40, 27,$this->subtitle);
        }
        $this->pdf->SetFont('DejaVu', '', 11);
        if ($this->page>1){
            $this->pdf->text(185,27,"page " . $this->page);
        }
        return $this->pdf;
    }

    public function pg_amounts($yr=""){
        if ($yr==""){
            $yr=date('Y');
        }
        $data=array();
        $amounts=Gift::where('paymentdate','>=',$yr.'-01-01')->where('paymentdate','<=',$yr.'-12-31')->orderBy('paymentdate','ASC')->get();
        foreach ($amounts as $amount){
            $data[date('nF Y',strtotime($amount->paymentdate))][$amount->pgnumber][]=$amount->amount;
        }
        if (!count($amounts)){
            return "No data has been captured for " . $yr;
        }
        $this->title="Planned givers by amount";
        $add=0;
        foreach ($data as $mth=>$month){
            ksort($month);
            $y=42;
            $add=0;
            $this->pdf=$this->report_header();
            $this->pdf->SetXY(180,20);
            $this->pdf->cell(20,0,substr($mth,1),0,0,'R');
            $this->pdf->text(13+$add,$y-7,"PG");
            $this->pdf->text(40+$add,$y-7,"Amount");
            $grand=0;
            foreach ($month as $pg=>$gifts){
                if ($y>280){
                    $y=42;
                    $add=$add+60;
                    $this->pdf->text(13+$add,$y-7,"PG");
                    $this->pdf->text(40+$add,$y-7,"Amount");
                }
                $total=0;
                foreach ($gifts as $gift){
                    $total=$total + floatval($gift);
                }
                $this->pdf->setxy(10+$add,$y);
                $this->pdf->cell(10,0,$pg,0,0,'R');
                $this->pdf->setxy(35+$add,$y);
                $this->pdf->cell(20,0,number_format($total),0,0,'R');
                $grand=$grand+$total;
                $y=$y+5;
            }
            $this->pdf->setxy(180,10);
            $this->pdf->cell(20,10,'R ' . number_format($grand),0,0,'R');
        }
        $this->pdf->Output('I','planned-giving-amounts');
    }

    public function pg_names(){
        $names=Individual::where('giving','>',0)->orderBy('surname')->whereNull('deleted_at')->get();
        $page=0;
        $this->title="Planned givers by name";
        $this->pdf=$this->report_header();
        $y=35;
        foreach ($names as $name){
            if ($y>280){
                $page++;
                $this->pdf=$this->report_header();
                $y=35;
            }
            $this->pdf->text(10,$y,$name->surname . ", " . $name->firstname . " (" . $name->giving . ")");
            $y=$y+5;
        }
        $this->pdf->Output('I','planned-giving-names');
    }

    public function pg_numbers(){
        $names=Individual::where('giving','>',0)->orderBy('giving','ASC')->whereNull('deleted_at')->get();
        $page=0;
        $this->title="Planned givers by number";
        $this->pdf=$this->report_header();
        $y=35;
        foreach ($names as $name){
            if ($y>280){
                $page++;
                $this->pdf=$this->report_header();
                $y=35;
            }
            $this->pdf->text(10,$y,$name->giving);
            $this->pdf->text(21,$y,$name->surname . ", " . $name->firstname);
            $y=$y+5;
        }
        $this->pdf->Output('I','planned-giving-names');
    }

    public function removenames(){
        $removals=Individual::whereHas('attendances')->where('memberstatus','<>','inactive')->whereNull('deleted_at')->orderBy('surname')->get();
        $this->title="Name tags not used in over 6 months";
        $this->pdf=$this->report_header();
        $this->pdf->SetFont('DejaVu', 'B', 14);
        $this->pdf->text(10, 35, "Name");
        $this->pdf->text(150, 35, "Last service");
        $this->pdf->line(10, 29, 190, 29);
        $this->pdf->SetFont('DejaVu', '', 12);
        $y=42;
        $remdate=strtotime('6 months ago');
        foreach ($removals as $removal){
            $stt=strtotime(substr($removal->lastseen,0,11));
            if ($stt<$remdate){
                $this->pdf->text(10,$y,strtoupper($removal->surname) . ", " . $removal->firstname);
                $this->pdf->text(150,$y,$removal->lastseen);
                $y=$y+5;
                if ($y > 280){
                    $this->pdf->AddPage('P');
                    $this->pdf->SetFont('DejaVu', 'B', 14);
                    $this->pdf->text(10, 16, "Name tags not used in over 6 months");
                    $this->pdf->text(10, 25, "Name");
                    $this->pdf->text(150, 25, "Last service");
                    $this->pdf->line(10, 29, 190, 29);
                    $this->pdf->SetFont('DejaVu', '', 12);
                    $y=35;  
                }
            }
        }
        $this->pdf->Output('I','name-tags-removal');
        exit;
    }

    public function roster(string $id, int $year, int $month, $period=1, $output=null) {
        for ($i=0;$i<$period;$i++){
            $reportdate = date('F Y',strtotime($year . '-' . $month . '-01'));
            $data = $this->getRosterData(date('Y-m',strtotime($year . '-' . $month . '-01')),$id);
            $roster = Roster::find($id);
            $this->title = $roster->roster . " (" . $reportdate . ")";
            $this->pdf->SetFillColor(0,0,0);
            $this->pdf->AddPage('L');
            $this->pdf->SetTitle($this->title);
            $this->pdf->SetAutoPageBreak(true, 0);
            $this->pdf->SetFont('DejaVu', 'B', 22);
            $this->pdf->Image($this->logo,10,0,25,25);
            $this->pdf->text(40, 12, setting('general.church_name'));
            $this->pdf->SetFont('DejaVu', '', 16);
            $this->pdf->text(40, 20, $this->title);
            $xx = 66;
            $this->pdf->SetFont('DejaVu', 'B', 12);
            if (count($data['columns'])==6){
                $add=-5;
            } elseif (count($data['columns'])==5){
                $add=0;
            } else {
                $add=10;
            }
            $this->pdf->rect(10,26,280,11,'F');
            $this->pdf->SetTextColor(255,255,255);
            foreach ($data['columns'] as $week) {
                if (isset($data['midweeks'][$week])){
                    $xx=$xx+$add;
                    $this->pdf->text($xx,31,$week);
                    $this->pdf->SetFont('DejaVu', '', 10);
                    $this->pdf->text($xx,35,$data['midweeks'][$week]);
                    $this->pdf->SetFont('DejaVu', 'B', 12);
                    $xx=$xx+44;
                } else {
                    $xx=$xx+$add;
                    $this->pdf->text($xx,33,$week);
                    $xx=$xx+44;
                }
            }
            $this->pdf->SetTextColor(0,0,0);
            $yy = 42;
            $max = 1;
            $first=true;
            foreach ($data['rows'] as $key=>$col) {
                $this->pdf->SetFont('DejaVu', 'B', 11);
                $this->pdf->text(10,1+$yy,$key);
                if ($first){
                    $first=false;
                } else {
                    $this->pdf->line(10, $yy-5, 290, $yy-5);
                }
                $xx = 22;
                $this->pdf->SetFont('DejaVu', '', 10.5);
                $max=1;
                foreach ($col as $kk=>$ii) {
                    if (($kk <> "id") and ($kk<>"extra")){
                        $xx=$xx+44+$add;
                        $count=0;
                        foreach ($ii as $pp){
                            if ($pp <>"-"){
                                if (strpos($pp,", ")){
                                    $this->pdf->text($xx,1+$yy+$count*5,substr($pp,2+strpos($pp,',')) . " " . substr($pp,0,strpos($pp,',')));
                                } else {
                                    $this->pdf->text($xx,1+$yy+$count*5,$pp);
                                }
                                $count++;
                            }
                            if ($count>$max){
                                $max=$count;
                            }
                        }
                    }
                }
                $yy=$yy+9*($max);
            }
            if (($period==2) && ($i==0)){
                if ($month==12){
                    $month=1;
                    $year=$year+1;
                } else {
                    $month=$month+1;
                }
            }
        }
        if ($output){
            return $this->pdf->Output('S');
        } else {
            $this->pdf->Output();
        }
        exit;
    }

    public function seriesplan($start=""){
        if (!$start){
            $start=date('Y-m-d');
        }
        $end = date('Y-m-d',strtotime($start . ' + 1 year - 1 day'));
        $this->title="Preaching series plan";
        $this->subtitle=date('j M Y',strtotime($start)) . " - " . date('j M Y',strtotime($end));
        $this->pdf=$this->report_header();
        $services = Service::with('series','person')->whereNotNull('video')->where('servicedate','>=',$start)->where('servicedate','<=',$end)->orderBy('servicedate','ASC')->get();
        $yy=35;
        $this->pdf->SetFont('DejaVu', 'B', 11);
        $this->pdf->text(10,$yy,"Date");
        $this->pdf->text(25,$yy,"Series");
        $this->pdf->text(90,$yy,"Reading");
        $this->pdf->text(170,$yy,"Preacher");
        $yy=42;
        foreach($services as $service){
            $this->pdf->SetFont('DejaVu', '', 10);
            $this->pdf->text(10,$yy,date('j M',strtotime($service->servicedate)));
            $this->pdf->SetFont('DejaVu', 'B', 10);
            if (isset($service->series->series)){
                $this->pdf->text(25,$yy,$service->series->series);
            }
            $this->pdf->SetFont('DejaVu', '', 10);
            $this->pdf->text(90,$yy,$service->reading);
            if ($service->person){
                $this->pdf->text(170,$yy,$service->person->fullname);
            }
            $yy=$yy+5;
        }
        $this->pdf->Output();
    }

    public function service ($id,$stime=""){
        $set=Service::with(['setitems' => function($q) { $q->orderBy('sortorder', 'asc'); }])->where('id',$id)->first();
        if (!$stime){
            $stime =  $set->servicetime;
        }
        $this->pdf->AddPage('P');
        $this->title=date("j F Y",strtotime($set->servicedate));
        $this->pdf->SetTitle($this->title . " - " . $stime);
        $this->pdf->SetAutoPageBreak(true, 0);
        $this->pdf->SetFont('DejaVu', 'B', 18);
        $song=url('/') . "/church/images/song.png";
        $prayer=url('/') . "/church/images/prayer.png";
        $this->pdf->Image($this->widelogo,123,8,77,30);
        $this->pdf->rect(19,10,53,7.5,'F');
        $this->pdf->SetTextColor(255,255,255);
        if ($stime) {
            $this->pdf->text(20, 16, $stime . " service");
        }
        $this->pdf->SetTextColor(0,0,0);
        $this->pdf->SetFont('DejaVu', '', 14);
        $this->pdf->text(20, 23, $this->title);
        $this->pdf->SetFont('DejaVu', 'B', 14);
        $this->pdf->text(20, 32, "Order of service");
        $this->pdf->line(20, 35, 195, 35);

        if (isset($set->series_id)){
            $this->pdf->rect(75,18,50,16);
            $this->pdf->SetFont('DejaVu', 'B', 12);
            $this->pdf->text(77,23,"Sermon Series");
            $this->pdf->SetFont('DejaVu', '', 10);
            $series=Series::find($set->series_id);
            $this->pdf->text(77,28,$series->series);
            $this->pdf->text(77,32,"Week: " . 1 + (strtotime($set->servicedate) - strtotime($series->startingdate)) / 604800);
        }
        $items=$set->setitems;
        $yy=44;
        $projectarray=['Bible re','Communio','Benedict','Lords Pr'];
        foreach ($items as $item){
            $item->extra = $this->GetExtraInfo($item);
            $this->pdf->SetFont('DejaVu', '', 14);
            if (!$item->setitemable_id){
                if (in_array(substr($item->note,0,8),$projectarray)){
                    $this->pdf->Image($prayer,10,$yy-4.5,8);
                }
                $this->pdf->text(20, $yy, $item->note);
                $width=$this->pdf->GetStringWidth($item->note);
                $this->pdf->SetFont('DejaVu', '', 10);
                $this->pdf->text(23+$width,$yy,$item->extra);
            } else {
                if ($item->setitemable_type=="song"){
                    $this->pdf->Image($song,12,$yy-4,4);
                    $this->pdf->SetFont('DejaVu', 'B', 14);
                } elseif ($item->setitemable_type=="prayer"){
                    $this->pdf->Image($prayer,10,$yy-4.5,8);
                }
                if ($item->note){
                    $this->pdf->text(20, $yy, $item->setitemable->title);
                    $width=$this->pdf->GetStringWidth($item->setitemable->title);
                    $this->pdf->SetFont('DejaVu', '', 10);
                    if (23+$width+$this->pdf->GetStringWidth($item->extra)>200){
                        $yy=$yy+5;
                        $this->pdf->text(30,$yy,$item->extra);
                    } else {
                        $this->pdf->text(23+$width,$yy,$item->extra);
                    }
                } else {
                    $this->pdf->text(20, $yy, $item->setitemable->title);
                    $width=$this->pdf->GetStringWidth($item->setitemable->title);
                    $this->pdf->SetFont('DejaVu', '', 10);
                    if (23+$width+$this->pdf->GetStringWidth($item->extra)>200){
                        $yy=$yy+5;
                        $this->pdf->text(30,$yy,$item->extra);
                    } else {
                        $this->pdf->text(23+$width,$yy,$item->extra);
                    }
                }
            }
            $yy=$yy+8;
        }
        $filename="OOS" . date('ymd',strtotime($set->servicedate)) . "_" . $stime;
        $rosters=array('Communion','Data Projector','Prayer','Society Stewards','Sound Desk','Welcome Team');
        $rosternotes=array();
        foreach ($rosters as $roster){
            $newnote=$this->addRoster($roster,$set->servicetime,$set->servicedate);
            if ($newnote !== $roster){
                $rosternotes[]=$newnote;
            }
        }
        $yy=258;
        if (count($rosternotes)){
            $yy=$yy-5*count($rosternotes);
            $this->pdf->rect(18,$yy-6,140,5*count($rosternotes)+9);
            $this->pdf->SetFont('DejaVu', 'B', 12);
            $this->pdf->text(20, $yy, "Roster");
            $this->pdf->SetFont('DejaVu', '', 11);
            foreach ($rosternotes as $rosternote){
                $yy=$yy+5;
                $this->pdf->text(20, $yy, $rosternote);
            }
        }
        $this->pdf->rect(18,$yy+5,180,27);
        $this->pdf->SetFont('DejaVuCond', 'B', 12);
        $this->pdf->text(20,$yy+9,"Feedback or suggestions");
        $this->pdf->SetFont('DejaVuCond', '', 11);
        $this->pdf->text(75,$yy+9,"(anything we can do to improve for next time)");
        $this->pdf->Output('I',$filename);
        exit;
    }
    
    private function setupRosternotes($service_id){
        $set=Service::find($service_id);
        $rosters=array('Communion','Data Projector','Prayer','Society Stewards','Sound Desk','Welcome Team');
        $rosternotes=array();
        foreach ($rosters as $roster){
            $newnote=$this->addRoster($roster,$set->servicetime,$set->servicedate);
            if ($newnote !== $roster){
                $rosternotes[]=$newnote;
            }
        }
        return $rosternotes;
    }
  
    public function song($id)
    {
        $song = Song::find($id);
        $this->pdf->AddPage('P');
        $this->pdf->SetTitle($song->title);
        $this->pdf->SetAutoPageBreak(true, 0);
        $this->pdf->SetFont('Courier', 'B', 14);
        $this->pdf->text(20, 16, $song->title);
        $this->pdf->SetFont('Courier', 'I', 10);
        $this->pdf->text(20, 22, $song->author);
        $this->pdf->SetFont('Courier', '', 10);
        $this->pdf->text(185, 16, 'Key: ' . $song->key);
        $this->pdf->text(190, 22, $song->tempo);
        $this->pdf->line(20, 26, 200, 26);
        $x=20;
        $lines=explode(PHP_EOL, $song->lyrics);
        $y=34;
        $vo=explode(" ",$song->verseorder);
        foreach ($lines as $line) {
            $line=$this->convert_smart_quotes($line);
            if (strpos($line, '}')) {
                $line=str_replace('{', '', $line);
                $line=str_replace('}', '', $line);
                $this->pdf->SetFont('Courier', 'B', 12);
                $this->pdf->SetTextColor(160, 160, 160);
                $y=$y+3.5;
                $shortline = substr($line, 0, 2);
                $this->pdf->text(13, $y, $shortline);
                $shortline=trim($shortline);
                $y=$y-3.5;
                $vos="";
                foreach ($vo as $kk=>$vv){
                    if ($vv==$shortline){
                        $vos.=1+$kk . " ";
                    }
                }
                if ($vos){
                    $vos=substr($vos,0,-1);
                }
                if (strlen($vos)>6){
                    if (substr($vos,7,1)==" "){
                        $this->pdf->text(170, $y+7, substr($vos,0,7));
                        $this->pdf->text(170, $y+12, substr(trim($vos," "),7));
                    } else {
                        $this->pdf->text(170, $y+7, substr($vos,0,6));
                        $this->pdf->text(170, $y+12, substr(trim($vos," "),6));
                    }
                } else {
                    $this->pdf->text(170, $y+7, $vos);
                }
                $this->pdf->SetTextColor(0, 0, 0);
            } else {
                $this->pdf->SetFont('Courier', '', 12);
                if (strpos($line, ']')) {
                    $y=$y+3.5;
                }
                $x=20;
                $addme=$x;
                $chordline="";
                $minlen=0;
                for ($i=0; $i<strlen($line); $i++) {
                    if ($line[$i]=='[') {
                        $chordsub=substr($line, $i);
                        $chor=substr($chordsub, 1, -1+strpos($chordsub, ']'));
                        $minlen=$this->pdf->GetStringWidth($chor);
                        $chordline.=$chor;
                        $this->pdf->SetFont('Courier', '', 12);
                        $i=$i+strlen($chor)+1;
                    } else {
                        $this->pdf->text($x, $y, $line[$i]);
                        if ($minlen ==0){
                            $chordline.=" ";
                        } else {
                            $minlen=$minlen-$this->pdf->GetStringWidth(" ");
                            if ($minlen < 0){
                                $minlen=0;
                            }
                        }
                        $x=$x+$this->pdf->GetStringWidth($line[$i]);
                    }
                }
                $this->pdf->SetFont('Courier', 'B', 12);
                $this->pdf->text(20, $y-3.5, $chordline);
                $this->pdf->SetFont('Courier', '', 12);
            }
            $y=$y+3.5;
        }
        
        // Chord list
        $this->pdf->SetTextColor(0,0,0);
        $y=26;
        $chords = $this->_getChords($song->lyrics);
        if (is_array($chords)){
            foreach ($chords as $chord) {
                $this->pdf->SetFont('Courier', '', 7);
                $dbchord = Chord::where('chord',$chord)->get();
                $x1=190;
                if (count($dbchord)) {
                    $this->pdf->setxy(180,$y);
                    $this->pdf->SetFont('Courier', 'B', 10);
                    $this->pdf->cell(30,5,$chord,0,0,'C');
                    if ($dbchord[0]->fret==0){
                        $this->pdf->line(190,$y+5,200,$y+5);
                        $f=0;
                    } else {
                        $f=1;
                        $this->pdf->text(202,$y+8,$dbchord[0]->fret);
                    }
                    for ($i=6;$i>0;$i--){
                        $svar="s" . $i;
                        if ($dbchord[0]->{$svar}=="x"){
                            $this->pdf->SetDrawColor(175,175,175);
                            $this->pdf->line($x1,$y+5,$x1,$y+17);
                        } else {
                            $this->pdf->SetDrawColor(0,0,0);
                            $this->pdf->line($x1,$y+5,$x1,$y+17);
                        }
                        $this->pdf->SetDrawColor(0,0,0);
                        $x1=$x1+2;
                        if ($i<6){
                            $this->pdf->line(190,2+$y+$i*3,200,2+$y+$i*3);
                        }
                    }
                    $x=188.5;
                    $cdata=array(
                        "s6"=>$dbchord[0]->s6,
                        "s5"=>$dbchord[0]->s5,
                        "s4"=>$dbchord[0]->s4,
                        "s3"=>$dbchord[0]->s3,
                        "s2"=>$dbchord[0]->s2,
                        "s1"=>$dbchord[0]->s1
                    );
                    foreach ($cdata as $cd){
                        if ($cd !== 'x'){
                            $cd = $cd - $dbchord[0]->fret + $f;
                            $this->pdf->SetFont('Courier', 'B', 14);
                            if ($cd > 0){
                                $this->pdf->SetFont('Courier', 'B', 20);
                                $circle=url('/') . "/church/images/circle.png";
                                $this->pdf->Image($circle,$x+0.5,$y+2.5+3*$cd,2,2);
                                $this->pdf->SetFont('Courier', 'B', 14);
                            }
                            $this->pdf->SetFont('Courier', '', 7);
                        }
                        $x=$x+2;
                    }
                } else {
                    $this->pdf->SetTextColor(125,125,125);
                    $this->pdf->setxy(180,$y);
                    $this->pdf->SetFont('Courier', 'B', 10);
                    $this->pdf->cell(30,5,$chord,0,0,'C');            
                    $this->pdf->SetTextColor(0,0,0);
                    $this->pdf->SetDrawColor(125,125,125);
                    for ($i=1;$i<7;$i++){
                        $this->pdf->line($x1,$y+5,$x1,$y+17);
                        $x1=$x1+2;
                        if ($i<6){
                            $this->pdf->line(190,2+$y+$i*3,200,2+$y+$i*3);
                        }
                    }
                    $this->pdf->SetFillColor(0,0,0);
                }
                $y=$y+18;
            }
        }
        $filename=Str::slug($song->title, "-");
        $this->pdf->Output('I',$filename);
        exit;
    }
    
    public function tasks(){
        $tasks=Task::where('status','<>','done')->get();
        $this->title="Outstanding tasks";
        $this->pdf=$this->report_header();
        $this->pdf->Output('I','Outstanding tasks');
        exit;
    }

    public function onTransposeUp($recordId){
        //return $this->_transposeLyrics(input("Song[lyrics]"),"up");
        /*$this->vars['pdfId']=$recordId;
        return [
            '#pdfIframe' => $this->makePartial('songpdf')
        ];*/
    }
    
    public function onTransposeDown(){
        //return $this->_transposeLyrics(input("Song[lyrics]"),"down");
    }
    
    private function _getChords($lyrics)
    {
        preg_match_all("/\[([^\]]*)\]/", $lyrics, $matches);
        $chords=array_unique($matches[1]);
        asort($chords);
        if (count($chords)) {
            return $chords;
        } else {
            return "";
        }
    }

    private function _moveOne($keys, $chord, $direction)
    {
        if (($chord=="A") and ($direction=="down")) {
            $ndx=11;
        } elseif (($chord=="A") and ($direction=="up")) {
            $ndx=1;
        } elseif ($direction=="up") {
            $ndx=array_search($chord, $keys)+1;
        } elseif ($direction=="down") {
            $ndx=array_search($chord, $keys)-1;
        } else {
            $ndx=array_search($chord, $keys);
        }
        return $keys[$ndx];
    }

    private function _transpose($chords, $direction)
    {
        $keys=array('A','Bb','B','C','C#','D','Eb','E','F','F#','G','G#','A');
        $newchords=array();
        foreach ($chords as $chord) {
            if ((strpos($chord, "#")==1) or (strpos($chord, 'b'))==1) {
                $chordpart=substr($chord, 0, 2);
            } else {
                $chordpart=substr($chord, 0, 1);
            }
            $newchordpart=$this->_moveOne($keys, $chordpart, $direction);
            if (strlen($chord)>strlen($chordpart)) {
                $chordrem=substr($chord, strlen($chordpart));
            } else {
                $chordrem="";
            }
            if ((strpos($chordrem, '/')===0) or (strpos($chordrem, '/')>0)) {
                $newbassnote=$this->_moveOne($keys, substr($chordrem, 1), $direction);
                $chordrem=substr($chordrem, 0, strpos($chordrem, '/')) . "/" . $newbassnote;
            }
            $newchords[]=$newchordpart . $chordrem;
        }
        return $newchords;
    }

    private function _transposeLyrics($lyrics, $direction)
    {
        $lyrics=str_replace('[A#', '[Bb', $lyrics);
        $lyrics=str_replace('[Db', '[C#', $lyrics);
        $lyrics=str_replace('[D#', '[Eb', $lyrics);
        $lyrics=str_replace('[Gb', '[F#', $lyrics);
        $lyrics=str_replace('[Ab', '[G#', $lyrics);
        $chords=$this->_getChords($lyrics);
        $newchords=$this->_transpose($chords, $direction);
        foreach ($chords as $key=>$chord) {
            $lyrics=str_replace('[' . $chord . ']', '$' . array_shift($newchords) . '%', $lyrics);
        }
        $lyrics=str_replace('$', '[', $lyrics);
        $lyrics=str_replace('%', ']', $lyrics);
        return $lyrics;
    }
    
    public function venue($id, $reportdate){
        $days=array();
        $fday=intval(date('N',strtotime($reportdate)));
        if ($fday==1){
            $monday=strtotime($reportdate);
        } else {
            $monday=strtotime($reportdate)-86400*($fday-1);
        }
        for ($i=0;$i<7;$i++){
            $days[$i+1]=date('Y-m-d',$monday+$i*86400);
        }
        $hours=['07h00','08h00','09h00','10h00','11h00','12h00','13h00','14h00','15h00','16h00','17h00','18h00','19h00','20h00','21h00','22h00'];
        $venue=Venue::find($id);
        $this->title = $venue->venue . " Bookings: " . date('j F',strtotime($days[1])) . " - " . date('j F Y',strtotime($days[7]));
        $this->pdf->SetFillColor(190,190,190);
        $this->pdf->AddPage('P');
        $this->pdf->SetTitle($this->title);
        $this->pdf->SetAutoPageBreak(true, 0);
        $this->pdf->SetFont('Arial', 'B', 22);
        $this->pdf->Image($this->logo,10,0,25,25);
        $this->pdf->text(40, 12, setting('general.church_name'));
        $this->pdf->SetFont('Arial', '', 16);
        $this->pdf->text(40, 20, $this->title);
        $this->pdf->SetFont('Arial', 'B', 12);
        $yy=40;
        foreach ($hours as $hh){
            if ($hh<>$hours[count($hours)-1]){
                $this->pdf->line(10,$yy-6,204,$yy-6);
                $this->pdf->text(13,$yy,$hh);
                $yy=$yy+17;
            }
        }
        $this->pdf->line(10,$yy-6,204,$yy-6);
        $this->pdf->line(10,34,10,289);
        $xx=35;
        foreach ($days as $dd){
            $bookings=Diaryentry::with('diarisable')->where(DB::raw('substr(diarydatetime, 1, 10)'),$dd)->where('venue_id',$id)->get();
            foreach ($bookings as $booking){
                $start=substr($booking->diarydatetime,11,5);
                $sh=substr($start,0,2);
                $sm=substr($start,3,2);
                $sy=array_search($sh."h00",$hours) * 17 + 40 + intval($sm)/60 * 17 - 6;
                $end=$booking->endtime;
                $eh=substr($end,0,2);
                $em=substr($end,3,2);
                $ey=array_search($eh."h00",$hours) * 17 + 40 + intval($em)/60 * 17 - 6;
                $this->pdf->rect($xx-6,$sy,25,$ey-$sy,'DF');
                $this->pdf->setxy($xx-6,$sy+1);
                $this->pdf->SetFont('Arial', '', 8);
                if (($booking->diarisable_id) and (isset($booking->diarisable->tenant))){
                    $msg=$booking->diarisable->tenant;
                    if ($booking->details){
                        $msg.=" (" . $booking->details . ")";
                    }
                    $this->pdf->multicell(25,4,$msg,0,'C');
                } elseif (($booking->diarisable_id) and (isset($booking->diarisable->groupname))){
                    $msg=$booking->diarisable->groupname;
                    if ($booking->details){
                        $msg.=" (" . $booking->details . ")";
                    }
                    $this->pdf->multicell(25,4,$msg,0,'C');
                } elseif (($booking->diarisable_id) and (isset($booking->diarisable->event))){
                    $msg=$booking->diarisable->event;
                    if ($booking->details){
                        $msg.=" (" . $booking->details . ")";
                    }
                    $this->pdf->multicell(25,4,$msg,0,'C');
                } elseif (($booking->diarisable_id) and (isset($booking->diarisable->course))){
                    $msg=$booking->diarisable->course;
                    if ($booking->details){
                        $msg.=" (" . $booking->details . ")";
                    }
                    $this->pdf->multicell(25,4,$msg,0,'C');
                }
                $this->pdf->SetFont('Arial', 'B', 12);
            }
            $this->pdf->line($xx-6,34,$xx-6,289);
            $this->pdf->text($xx,32,date('D j',strtotime($dd)));
            $xx=$xx+25;
        }
        $this->pdf->line($xx-6,34,$xx-6,289);
        $this->pdf->Output();
        exit;
    }

    
}
