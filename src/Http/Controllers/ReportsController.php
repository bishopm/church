<?php

namespace Bishopm\Church\Http\Controllers;

use Bishopm\Church\Classes\Fpdf as Pdf;
use Bishopm\Church\Http\Controllers\Controller;
use Bishopm\Church\Models\Chord;
use Bishopm\Church\Models\Course;
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
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;

class ReportsController extends Controller
{
    public function a4meeting ($recordId){
        $mtg=Meeting::with(['agendaitems' => function($q) { $q->orderBy('sortorder', 'asc'); }])->where('id',$recordId)->first();
        $pdf = new Fpdf;
        $pdf->AddPage('P');
        $title=date("j F Y H:i",strtotime($mtg->meetingdatetime));
        $pdf->SetTitle($title);
        $pdf->SetAutoPageBreak(true, 0);
        $pdf->SetFont('Helvetica', 'B', 18);
        $logo=url('/') . "/public/church/images/bwidelogo.png";
        $pdf->Image($logo,123,8,77,30);
        $pdf->text(20, 16, $mtg->details);
        $pdf->SetFont('Helvetica', '', 14);
        $pdf->text(20, 23, $title);
        $pdf->SetFont('Helvetica', 'B', 14);
        $pdf->text(20, 32, "Agenda");
        $pdf->line(20, 35, 195, 35);
        $items=$mtg->agendaitems;
        $yy=44;
        $ndx=0;
        foreach ($items as $item){
            if ($item->level==1){
                $pdf->SetFont('Helvetica', 'B', 12);
                $ndx=intval(floor($ndx)+1);
                $pdf->text(20, $yy, $ndx . "  " . $item->heading);
            } else {
                $yy=$yy-2;
                $pdf->SetFont('Helvetica', '', 11);
                $ndx=$ndx+0.1;
                $pdf->text(25, $yy, $ndx . "  " . $item->heading);
            }
            $yy=$yy+8;
        }
        $filename=$title;
        $pdf->Output('I',$filename);
        exit;
    }

    public function a5meeting ($recordId){
        $mtg=Meeting::with(['agendaitems' => function($q) { $q->orderBy('sortorder', 'asc'); }])->where('id',$recordId)->first();
        $pdf = new Fpdf;
        $pdf->AddPage('L');
        $xadd=147;
        $title=date("j F Y H:i",strtotime($mtg->meetingdatetime));
        $pdf->SetTitle($title);
        $pdf->SetAutoPageBreak(true, 0);
        $pdf->SetFont('Helvetica', 'B', 15);
        $logo=url('/') . "/public/church/images/bwidelogo.png";
        $pdf->Image($logo,85,8,60);
        $pdf->Image($logo,85+$xadd,8,60);
        $pdf->text(10, 11, $mtg->details);
        $pdf->text(10+$xadd, 11, $mtg->details);
        $pdf->SetFont('Helvetica', '', 12);
        $pdf->text(10, 18, $title);
        $pdf->text(10+$xadd, 18, $title);
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->text(10, 27, "Agenda");
        $pdf->text(10+$xadd, 27, "Agenda");
        $pdf->line(10, 30, 142, 30);
        $pdf->line($xadd+10, 30, $xadd+142, 30);
        $items=$mtg->agendaitems;
        $yy=39;
        $ndx=0;
        foreach ($items as $item){
            if ($item->level==1){
                $pdf->SetFont('Helvetica', 'B', 11);
                $ndx=intval(floor($ndx)+1);
                $pdf->text(10, $yy, $ndx . "  " . $item->heading);
                $pdf->text($xadd+10, $yy, $ndx . "  " . $item->heading);
            } else {
                $yy=$yy-2;
                $pdf->SetFont('Helvetica', '', 10);
                $ndx=$ndx+0.1;
                $pdf->text(15, $yy, $ndx . "  " . $item->heading);
                $pdf->text($xadd+15, $yy, $ndx . "  " . $item->heading);
            }
            $yy=$yy+7;
        }
        $filename=$title;
        $pdf->Output('I',$filename);
        exit;
    }

    private function addRoster($label,$servicetime,$servicedate){
        if ($label=="Bible reading"){
            $groupname="Readers " . $servicetime;
        } elseif ($label=="Society Stewards") {
            $groupname=$label;
        } else {
            $groupname=$label . " " . $servicetime;
        }
        if (substr($groupname,0,1) == "B"){
            dd($groupname);
        }
        $group=Group::where('groupname',$groupname)->first();
        if ($group){
            $group_id=$group->id;
            if ($label<>"Society Stewards"){
                $rgroup=Rostergroup::where('group_id',$group_id)->first();
                if ($rgroup){
                    $rostergroup=$rgroup->id;
                    $rosteritem=Rosteritem::with('individuals')->where('rostergroup_id',$rostergroup)->where('rosterdate',$servicedate)->first();
                } 
            } else {
                $rostergroups=Rostergroup::with('roster')->where('group_id',$group_id)->get();
                foreach ($rostergroups as $rg){
                    if (str_contains($rg->roster->roster,$servicetime)){
                        $rosteritem=Rosteritem::with('individuals')->where('rostergroup_id',$rg->id)->where('rosterdate',$servicedate)->first();
                    }
                }
            }
            if ((isset($rosteritem)) and ($rosteritem->individuals)){
                $indivs=array();
                foreach ($rosteritem->individuals as $ind){
                    $indivs[]=$ind->firstname . " " . $ind->surname;
                }
                if ($label=="Society Stewards"){
                    $label = "Society Steward: " . implode(", ", $indivs);
                } elseif ($label=="Bible reading") {
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
        $title = "Venue Bookings: " . date('j F Y',strtotime($reportdate));
        $pdf = new Fpdf;
        $pdf->SetFillColor(190,190,190);
        $pdf->AddPage('L');
        $pdf->SetTitle($title);
        $pdf->SetAutoPageBreak(true, 0);
        $pdf->SetFont('Arial', 'B', 22);
        $image=url('/') . "/public/church/images/blacklogo.png";
        $pdf->Image($image,10,0,25,25);
        $pdf->text(40, 12, setting('general.church_name'));
        $pdf->SetFont('Arial', '', 16);
        $pdf->text(40, 20, $title);
        $pdf->SetFont('Arial', 'B', 12);
        $yy=40;
        $xx=35;
        $col=floor(254/count($venues));
        $width=$col*count($venues)+$xx-6;
        foreach ($hours as $hh){
            if ($hh<>$hours[count($hours)-1]){
                $pdf->line(10,$yy-6,$width,$yy-6);
                $pdf->text(13,$yy+1,$hh);
                $yy=$yy+11;
            }
        }
        $pdf->line(10,34,10,199);
        $pdf->line($width,34,$width,199);
        $pdf->line(10,$yy-6,$width,$yy-6);
        foreach ($venues as $venue){
            $bookings=Diaryentry::with('diarisable')->where(DB::raw('substr(diarydatetime, 1, 10)'),$reportdate)->where('venue_id',$venue->id)->get();
            $pdf->SetFont('Arial', '', 10);
            $pdf->setxy($xx-6,30);
            $pdf->cell($col,0,$venue->venue,0,0,'C');
            foreach ($bookings as $booking){
                $start=substr($booking->diarydatetime,11,5);
                $sh=substr($start,0,2);
                $sm=substr($start,3,2);
                $sy=array_search($sh."h00",$hours) * 11 + 40 + intval($sm)/60 * 11 - 6;
                $end=$booking->endtime;
                $eh=substr($end,0,2);
                $em=substr($end,3,2);
                $ey=array_search($eh."h00",$hours) * 11 + 40 + intval($em)/60 * 11 - 6;
                $pdf->rect($xx-6,$sy,$col,$ey-$sy,'DF');
                $pdf->setxy($xx-6,$sy+1);
                $pdf->SetFont('Arial', '', 8);
                if (($booking->diarisable_id) and (isset($booking->diarisable->tenant))){
                    $msg=$booking->diarisable->tenant;
                    if ($booking->details){
                        $msg.=" (" . $booking->details . ")";
                    }
                    $pdf->multicell($col,3,$msg,0,'C');
                } elseif (($booking->diarisable_id) and (isset($booking->diarisable->groupname))){
                    $msg=$booking->diarisable->groupname;
                    if ($booking->details){
                        $msg.=" (" . $booking->details . ")";
                    }
                    $pdf->multicell($col,3,$msg,0,'C');
                } elseif (($booking->diarisable_id) and (isset($booking->diarisable->event))){
                    $msg=$booking->diarisable->event;
                    if ($booking->details){
                        $msg.=" (" . $booking->details . ")";
                    }
                    $pdf->multicell($col,3,$msg,0,'C');
                }
                $pdf->SetFont('Arial', 'B', 12);
            }
            $pdf->line($xx-6,34,$xx-6,199);
            $xx=$xx+$col;
        }
        $pdf->line($xx-6,34,$xx-6,199);
        $pdf->Output();
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
        $pdf = new Pdf('P','mm','A4');
        $pdf->AddPage('P');
        $pdf->SetFont('Arial', 'B', 11);
        
        $yy=10;
        $xx=10;
        $image=url('/') . "/public/church/images/blacklogo.png";
        if (count($individuals)){
            foreach ($individuals as $indiv){
                if ($yy>130){
                    $pdf->AddPage('P');
                    $yy=10;
                }
                $pdf->rect($xx,$yy,93,114);
                $pdf->Image($image,$xx+70,$yy+57.5,20,20);
                $pdf->setxy($xx+4,$yy+24);
                $font=60;
                $size="unknown";
                do {
                    $pdf->SetFont('Arial', 'B', $font);
                    if ($indiv['firstname']){
                        $width=$pdf->GetStringWidth($indiv['firstname']);
                    } else {
                        $width=0;
                    }
                    if ($width < 85){
                        $pdf->cell(86,0,$indiv['firstname'],0,0,'C');
                        $size="known";
                        $font=8;
                    } else {
                        $font=$font-0.5;
                    }
                } while ($size=="unknown");
                $pdf->setxy($xx+4,$yy+40);
                $font=25;
                $size="unknown";
                do {
                    $pdf->SetFont('Arial', '', $font);
                    $width=$pdf->GetStringWidth($indiv['surname']);
                    if ($width < 85){
                        $pdf->cell(86,0,$indiv['surname'],0,0,'C');
                        $size="known";
                        $font=8;
                    } else {
                        $font=$font-0.5;
                    }
                } while ($size=="unknown");
                $pdf->SetFont('Arial', 'B', 11);
                $pdf->SetDrawColor(185,185,185);
                $pdf->line($xx,$yy+57,$xx+93,$yy+57);
                $pdf->SetDrawColor(0,0,0);
                $pdf->Code39($xx+5,$yy+60,$indiv['id'],1.5,10);
                $pdf->setxy($xx+4,$yy+72);
                $pdf->cell(100,0,$indiv['firstname'] . " " . $indiv['surname'],0,0,'L');
                if ($xx==10){
                    $xx=110;    
                } else {
                    $xx=10;
                    $yy=$yy+120;
                }
            }
            if ($long){
                $pdf->AddPage('P');
                $pdf->rect(10,10,93,114);
                $pdf->RoundedRect(15,15,83,47,2);
                $pdf->text(15,73,"First name and surname");
                $pdf->RoundedRect(15,74,83,12,1);
                $pdf->text(15,91,"Cellphone");
                $pdf->RoundedRect(15,92,83,12,1);
                $pdf->text(15,108,"Email");
                $pdf->RoundedRect(15,109,83,12,1);
                
                $pdf->rect(110,10,93,114);
                $pdf->RoundedRect(115,15,83,47,2);
                $pdf->text(115,73,"First name and surname");
                $pdf->RoundedRect(115,74,83,12,1);
                $pdf->text(115,91,"Cellphone");
                $pdf->RoundedRect(115,92,83,12,1);
                $pdf->text(115,108,"Email");
                $pdf->RoundedRect(115,109,83,12,1);
                
                $pdf->rect(10,130,93,114);
                $pdf->RoundedRect(15,135,83,47,2);
                $pdf->text(15,193,"First name and surname");
                $pdf->RoundedRect(15,194,83,12,1);
                $pdf->text(15,211,"Cellphone");
                $pdf->RoundedRect(15,212,83,12,1);
                $pdf->text(15,228,"Email");
                $pdf->RoundedRect(15,229,83,12,1);
                
                $pdf->rect(110,130,93,114);
                $pdf->RoundedRect(115,135,83,47,2);
                $pdf->text(115,193,"First name and surname");
                $pdf->RoundedRect(115,194,83,12,1);
                $pdf->text(115,211,"Cellphone");
                $pdf->RoundedRect(115,212,83,12,1);
                $pdf->text(115,228,"Email");
                $pdf->RoundedRect(115,229,83,12,1);
                
                $pdf->SetFont('Arial', 'B', 11);
                $pdf->SetDrawColor(185,185,185);
                $pdf->line(10,67,103,67);
                $pdf->line(110,67,203,67);
                $pdf->line(10,187,103,187);
                $pdf->line(110,187,203,187);
                $pdf->SetDrawColor(0,0,0);
            }
        } else {
            $pdf->text(10,10,"No new members have been added to the system since last Sunday");
        }
        $filename=base_path() . "/storage/barcodes/barcodes.pdf";
        $pdf->Output($filename,'F');
        return Redirect::to('/storage/barcodes/barcodes.pdf');
    }

    public function calendar($yr=""){
        if (!$yr){
            $yr=date('Y');
        }
        $meetings=Meeting::with('venue')->where('meetingdatetime','>=',$yr . '-01-01')->where('meetingdatetime','<=',$yr . '-12-31')->orderBy('meetingdatetime','ASC')->where('calendar',1)->get();
        $events=Event::with('venue')->where('eventdate','>=',$yr . '-01-01')->where('eventdate','<=',$yr . '-12-31')->orderBy('eventdate','ASC')->where('calendar',1)->get();
        $courses=Course::with('venue')->where('coursedate','>=',$yr . '-01-01')->where('coursedate','<=',$yr . '-12-31')->orderBy('coursedate','ASC')->where('calendar',1)->get();
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
        $pdf = new Fpdf;
        $title = $yr . " Calendar";
        $pdf->SetTitle($title);
        $pdf->AddPage('P');
        $pdf->SetAutoPageBreak(true, 0);
        $pdf->SetFont('Helvetica', 'B', 22);
        $image=url('/') . "/public/church/images/blacklogo.png";
        $pdf->Image($image,10,5,25,25);
        $pdf->text(40, 17, setting('general.church_name'));
        $pdf->SetFont('Helvetica', '', 16);
        $pdf->text(40, 25, $title);
        $pdf->SetFont('Helvetica', 'B', 14);
        $pdf->line(10, 29, 200, 29);
        $pdf->SetFont('Helvetica', '', 12);
        $y=35;
        ksort($dates);
        $month="";
        foreach ($dates as $day){
            foreach ($day as $date){
                if ($month<>date('F',strtotime($date['datetime']))){
                    $month=date('F',strtotime($date['datetime']));
                    $pdf->SetFont('Helvetica', 'B', 12);
                    $pdf->text(10,$y+2,$month);
                    $pdf->SetFont('Helvetica', '', 12);
                    $y=$y+7;
                }
                $pdf->text(10,$y,date('d M (D)',strtotime($date['datetime'])));
                $pdf->text(37,$y,date('H:i',strtotime($date['datetime'])));
                $pdf->text(52,$y,$date['details']);
                $pdf->text(150,$y,$date['venue']);
                $y=$y+5;
                if ($y > 280){
                    $pdf->AddPage('P');
                    $pdf->SetFont('Helvetica', 'B', 22);
                    $image=url('/') . "/public/church/images/colouredlogo.png";
                    $pdf-> Image($image,10,0,25,25);
                    $pdf->text(40, 12, setting('general.church_name'));
                    $pdf->SetFont('Helvetica', '', 16);
                    $pdf->text(40, 20, $title);
                    $pdf->SetFont('Helvetica', 'B', 14);
                    $pdf->line(10, 24, 200, 24);
                    $pdf->SetFont('Helvetica', '', 12);
                    $y=32;  
                }
            }
        }
        $pdf->Output('I','Calendar');
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
                return $set->reading . " (" . $this->addRoster("Bible reading",$set->servicetime,$set->servicedate) . ")";
            } elseif ($setitem->note=="Sermon"){
                $url="https://methodist.church.net.za/preacher/" . setting('services.society_id') . "/" . $set->servicetime . "/" . substr($set->servicedate,0,10);
                $response=Http::get($url);
                $extra = $response->body();
                if ((isset($set->series)) and ($set->series->series !== "")) {
                    $extra = $extra . " (" . $set->series->series . ")";
                }
                return $extra;
            }
        }
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
        $data['columns'][]=date('j M Y',strtotime($firstdate));
        $data['columns'][]=date('j M Y',strtotime($firstdate . '+1 week'));
        $data['columns'][]=date('j M Y',strtotime($firstdate . '+2 week'));
        $data['columns'][]=date('j M Y',strtotime($firstdate . '+3 week'));
        if (date('m',strtotime($firstdate . '+4 week')) == date('m',strtotime($today))){
            $data['columns'][]=date('j M Y',strtotime($firstdate . '+4 week'));
        }
        $groups = DB::table('rosters')->join('rostergroups', 'rosters.id', '=', 'rostergroups.roster_id')
            ->join('groups', 'rostergroups.group_id', '=', 'groups.id')
            ->select('groupname','groups.id','rostergroups.extrainfo')
            ->where('rosters.id',$id)
            ->orderBy('groupname')
            ->get();
        foreach ($groups as $group){
            $data['rows'][$group->groupname]['id']=$group->id;
            if ($group->extrainfo=='yes'){
                $data['rows'][$group->groupname]['extra']='yes';
            } 
            foreach ($data['columns'] as $col){
                $fixdate=date('Y-m-d',strtotime($col));
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
        return $data;
    }

    public function group($id)
    {
        $group = Group::with('individuals')->find($id);
        $pdf = new Fpdf;
        $pdf->AddPage('P');
        $pdf->SetAutoPageBreak(true, 0);
        $pdf->SetFont('Helvetica', 'B', 18);
        $pdf->text(15, 16, setting('general.church_name'));
        $pdf->SetFont('Helvetica', '', 16);
        $pdf->text(15, 23, $group->groupname);
        $pdf->SetTitle($group->groupname);
        $pdf->SetFont('Helvetica', '', 12);
        $pdf->text(173, 23, date('Y-m-d'));
        $pdf->line(15, 26, 195, 26);
        $yy=35;
        $indivs = array();
        foreach ($group->individuals as $indiv) {
            $cc=$indiv->cellphone;
            $indivs[$indiv->surname . ', ' . $indiv->firstname] = substr($cc,0,3) . " " . substr($cc,3,3) . " " . substr($cc,6,4);
        }
        ksort($indivs);
        foreach ($indivs as $kk=>$ii) {
            if ($yy>285){
                $yy=35;
                $pdf->AddPage('P');
                $pdf->SetFont('Helvetica', 'B', 18);
                $pdf->text(15, 16, setting('general.church_name'));
                $pdf->SetFont('Helvetica', '', 16);
                $pdf->text(15, 23, $group->groupname);
                $pdf->SetTitle($group->groupname);
                $pdf->SetFont('Helvetica', '', 12);
                $pdf->text(173, 23, date('Y-m-d'));
                $pdf->line(15, 26, 195, 26);
            }
            $pdf->SetFont('Helvetica', 'B', 12);
            $pdf->text(15, $yy, $kk);
            $pdf->SetFont('Helvetica', '', 12);
            $pdf->text(169, $yy, utf8_decode($ii));
            $yy=$yy+6;
        }
        $pdf->Output();
        exit;
    }

    public function removenames(){
        $removals=Individual::whereHas('attendances')->where('memberstatus','<>','inactive')->orderBy('surname')->get();
        $pdf = new Fpdf;
        $pdf->AddPage('P');
        $pdf->SetAutoPageBreak(true, 0);
        $pdf->SetFont('Helvetica', 'B', 14);
        $pdf->text(10, 16, "Name tags not used in over 6 months");
        $pdf->text(10, 25, "Name");
        $pdf->text(150, 25, "Last service");
        $pdf->line(10, 29, 190, 29);
        $pdf->SetFont('Helvetica', '', 12);
        $y=35;
        $remdate=strtotime('6 months ago');
        foreach ($removals as $removal){
            $stt=strtotime(substr($removal->lastseen,0,11));
            if ($stt<$remdate){
                $pdf->text(10,$y,strtoupper($removal->surname) . ", " . $removal->firstname);
                $pdf->text(150,$y,$removal->lastseen);
                $y=$y+5;
                if ($y > 280){
                    $pdf->AddPage('P');
                    $pdf->SetFont('Helvetica', 'B', 14);
                    $pdf->text(10, 16, "Name tags not used in over 6 months");
                    $pdf->text(10, 25, "Name");
                    $pdf->text(150, 25, "Last service");
                    $pdf->line(10, 29, 190, 29);
                    $pdf->SetFont('Helvetica', '', 12);
                    $y=35;  
                }
            }
        }
        $pdf->Output('I','name-tags-removal');
        exit;
    }

    public function roster(string $id, int $year, int $month, $period=1, $output=null) {
        $pdf = new Fpdf();
        for ($i=0;$i<$period;$i++){
            $reportdate = date('F Y',strtotime($year . '-' . $month . '-01'));
            $data = $this->getRosterData(date('Y-m',strtotime($year . '-' . $month . '-01')),$id);
            $roster = Roster::find($id);
            $title = $roster->roster . " (" . $reportdate . ")";
            $pdf->SetFillColor(0,0,0);
            $pdf->AddPage('L');
            $pdf->SetTitle($title);
            $pdf->SetAutoPageBreak(true, 0);
            $pdf->SetFont('Helvetica', 'B', 22);
            $image=url('/') . "/public/church/images/colouredlogo.png";
            $pdf-> Image($image,10,0,25,25);
            $pdf->text(40, 12, setting('general.church_name'));
            $pdf->SetFont('Helvetica', '', 16);
            $pdf->text(40, 20, $title);
            $xx = 66;
            $pdf->SetFont('Helvetica', 'B', 12);
            if (count($data['columns'])==5){
                $add=0;
            } else {
                $add=10;
            }
            $pdf->rect(10,26,280,11,'F');
            $pdf->SetTextColor(255,255,255);
            foreach ($data['columns'] as $week) {
                $xx=$xx+$add;
                $pdf->text($xx,33,$week);
                $xx=$xx+44;
            }
            $pdf->SetTextColor(0,0,0);
            $yy = 42;
            $max = 1;
            $first=true;
            foreach ($data['rows'] as $key=>$col) {
                $pdf->SetFont('Helvetica', 'B', 11);
                $pdf->text(10,1+$yy,$key);
                if ($first){
                    $first=false;
                } else {
                    $pdf->line(10, $yy-5, 290, $yy-5);
                }
                $xx = 22;
                $pdf->SetFont('Helvetica', '', 10.5);
                $max=1;
                foreach ($col as $kk=>$ii) {
                    if (($kk <> "id") and ($kk<>"extra")){
                        $xx=$xx+44+$add;
                        $count=0;
                        foreach ($ii as $pp){
                            if ($pp <>"-"){
                                if (strpos($pp,", ")){
                                    $pdf->text($xx,1+$yy+$count*5,substr($pp,2+strpos($pp,',')) . " " . substr($pp,0,strpos($pp,',')));
                                } else {
                                    $pdf->text($xx,1+$yy+$count*5,$pp);
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
            $pdf->Output('F', storage_path('app/public/attachments/WMCrosters.pdf'));
            return;
        } else {
            $pdf->Output();
        }
        exit;
    }

    public function service ($id,$stime=""){
        $set=Service::with(['setitems' => function($q) { $q->orderBy('sortorder', 'asc'); }])->where('id',$id)->first();
        if (!$stime){
            $stime =  $set->servicetime;
        }
        $pdf = new Fpdf;
        $pdf->AddPage('P');
        $title=date("j F Y",strtotime($set->servicedate));
        $pdf->SetTitle($title . " - " . $set->servicetime);
        $pdf->SetAutoPageBreak(true, 0);
        $pdf->SetFont('Helvetica', 'B', 18);
        $logo=url('/') . "/public/church/images/bwidelogo.png";
        $song=url('/') . "/public/church/images/song.png";
        $prayer=url('/') . "/public/church/images/prayer.png";
        $pdf->Image($logo,123,8,77,30);
        $pdf->rect(19,10,45,7.5,'F');
        $pdf->SetTextColor(255,255,255);
        if ($stime) {
            $pdf->text(20, 16, $stime . " service");
        }
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Helvetica', '', 14);
        $pdf->text(20, 23, $title);
        $pdf->SetFont('Helvetica', 'B', 14);
        $pdf->text(20, 32, "Order of service");
        $pdf->line(20, 35, 195, 35);

        if (isset($set->series_id)){
            $pdf->rect(70,18,50,16);
            $pdf->SetFont('Helvetica', 'B', 12);
            $pdf->text(72,23,"Sermon Series");
            $pdf->SetFont('Helvetica', '', 10);
            $series=Series::find($set->series_id);
            $pdf->text(72,28,$series->series);
            $pdf->text(72,32,"Week: " . 1 + (strtotime($set->servicedate) - strtotime($series->startingdate)) / 604800);
        }
        $items=$set->setitems;
        $yy=44;
        $projectarray=['Bible re','Communio','Benedict','Lords Pr'];
        foreach ($items as $item){
            $item->extra = $this->GetExtraInfo($item);
            $pdf->SetFont('Helvetica', '', 14);
            if (!$item->setitemable_id){
                if (in_array(substr($item->note,0,8),$projectarray)){
                    $pdf->Image($prayer,10,$yy-4.5,8);
                }
                /*if ($item->note == "Notices"){
                    $notices = Notice::where('servicedate',$set->servicedate)->get();
                    $pdf->text(20, $yy, "Notices");
                    $pdf->SetFont('Helvetica', '', 10);
                    $noticesy = $yy;
                    foreach ($notices as $ndx => $notice){
                        $items = true;
                        if ($ndx < 1) {
                            $pdf->text(38,$yy," (* indicates that there is a slide to display)");
                        }
                        $yy=$yy+5;
                        if ($notice->slide == 1){
                            $pdf->text(21,$yy+1.5,"*");
                        }
                        $pdf->setxy(23,$yy-2);
                        $pdf->multicell(0, 4, 1+$ndx . ". " . $notice->details, 0, 'L');
                        $yy = $pdf->gety()-4;
                        $noticefy = $yy;
                    }
                    if (isset($noticefy)){
                        $pdf->rect(18,$noticesy-5,184,$noticefy-$noticesy+9);
                        $yy=$yy+2;   
                    }
                } else {*/
                $pdf->text(20, $yy, $item->note);
                $width=$pdf->GetStringWidth($item->note);
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->text(23+$width,$yy,$item->extra);
                //}
            } else {
                if ($item->setitemable_type=="song"){
                    $pdf->Image($song,12,$yy-4,4);
                    $pdf->SetFont('Helvetica', 'B', 14);
                } elseif ($item->setitemable_type=="prayer"){
                    $pdf->Image($prayer,10,$yy-4.5,8);
                }
                if ($item->note){
                    $pdf->text(20, $yy, $item->note);
                    $width=$pdf->GetStringWidth($item->note);
                    $pdf->SetFont('Helvetica', '', 10);
                    if (23+$width+$pdf->GetStringWidth($item->extra)>200){
                        $yy=$yy+5;
                        $pdf->text(30,$yy,$item->extra);
                    } else {
                        $pdf->text(23+$width,$yy,$item->extra);
                    }
                } else {
                    $pdf->text(20, $yy, $item->setitemable->title);
                    $width=$pdf->GetStringWidth($item->setitemable->title);
                    $pdf->SetFont('Helvetica', '', 10);
                    if (23+$width+$pdf->GetStringWidth($item->extra)>200){
                        $yy=$yy+5;
                        $pdf->text(30,$yy,$item->extra);
                    } else {
                        $pdf->text(23+$width,$yy,$item->extra);
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
            $pdf->rect(18,$yy-6,140,5*count($rosternotes)+9);
            $pdf->SetFont('Helvetica', 'B', 12);
            $pdf->text(20, $yy, "Roster");
            $pdf->SetFont('Helvetica', '', 11);
            foreach ($rosternotes as $rosternote){
                $yy=$yy+5;
                $pdf->text(20, $yy, $rosternote);
            }
        }
        $pdf->rect(18,$yy+5,180,27);
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->text(20,$yy+9,"Feedback or suggestions");
        $pdf->SetFont('Helvetica', '', 11);
        $pdf->text(72,$yy+9,"(anything we can do to improve for next time)");
        $pdf->Output('I',$filename);
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
        $pdf = new Fpdf;
        // define('FPDF_FONTPATH','/public/church/fonts/');
        $pdf->AddPage('P');
        $pdf->SetTitle($song->title);
        $pdf->SetAutoPageBreak(true, 0);
        $pdf->SetFont('Courier', 'B', 14);
        $pdf->text(20, 16, utf8_decode($song->title));
        $pdf->SetFont('Courier', 'I', 10);
        $pdf->text(20, 22, utf8_decode($song->author));
        $pdf->SetFont('Courier', '', 10);
        $pdf->text(185, 16, 'Key: ' . $song->key);
        $pdf->text(190, 22, $song->tempo);
        $pdf->line(20, 26, 200, 26);
        $x=20;
        $lines=explode(PHP_EOL, $song->lyrics);
        $y=34;
        $vo=explode(" ",$song->verseorder);
        foreach ($lines as $line) {
            $line=$this->convert_smart_quotes($line);
            if (strpos($line, '}')) {
                $line=str_replace('{', '', $line);
                $line=str_replace('}', '', $line);
                $pdf->SetFont('Courier', 'B', 12);
                $pdf->SetTextColor(160, 160, 160);
                $y=$y+3.5;
                $shortline = substr($line, 0, 2);
                $pdf->text(13, $y, $shortline);
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
                        $pdf->text(170, $y+7, substr($vos,0,7));
                        $pdf->text(170, $y+12, substr(trim($vos," "),7));
                    } else {
                        $pdf->text(170, $y+7, substr($vos,0,6));
                        $pdf->text(170, $y+12, substr(trim($vos," "),6));
                    }
                } else {
                    $pdf->text(170, $y+7, $vos);
                }
                $pdf->SetTextColor(0, 0, 0);
            } else {
                $pdf->SetFont('Courier', '', 12);
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
                        $minlen=$pdf->GetStringWidth($chor);
                        $chordline.=$chor;
                        $pdf->SetFont('Courier', '', 12);
                        $i=$i+strlen($chor)+1;
                    } else {
                        $pdf->text($x, $y, $line[$i]);
                        if ($minlen ==0){
                            $chordline.=" ";
                        } else {
                            $minlen=$minlen-$pdf->GetStringWidth(" ");
                            if ($minlen < 0){
                                $minlen=0;
                            }
                        }
                        $x=$x+$pdf->GetStringWidth($line[$i]);
                    }
                }
                $pdf->SetFont('Courier', 'B', 12);
                $pdf->text(20, $y-3.5, $chordline);
                $pdf->SetFont('Courier', '', 12);
            }
            $y=$y+3.5;
        }
        
        // Chord list
        $pdf->SetTextColor(0,0,0);
        $y=26;
        $chords = $this->_getChords($song->lyrics);
        if (is_array($chords)){
            foreach ($chords as $chord) {
                $pdf->SetFont('Courier', '', 7);
                $dbchord = Chord::where('chord',$chord)->get();
                $x1=190;
                if (count($dbchord)) {
                    $pdf->setxy(180,$y);
                    $pdf->SetFont('Courier', 'B', 10);
                    $pdf->cell(30,5,$chord,0,0,'C');
                    if ($dbchord[0]->fret==0){
                        $pdf->line(190,$y+5,200,$y+5);
                        $f=0;
                    } else {
                        $f=1;
                        $pdf->text(202,$y+8,$dbchord[0]->fret);
                    }
                    for ($i=6;$i>0;$i--){
                        $svar="s" . $i;
                        if ($dbchord[0]->{$svar}=="x"){
                            $pdf->SetDrawColor(175,175,175);
                            $pdf->line($x1,$y+5,$x1,$y+17);
                        } else {
                            $pdf->SetDrawColor(0,0,0);
                            $pdf->line($x1,$y+5,$x1,$y+17);
                        }
                        $pdf->SetDrawColor(0,0,0);
                        $x1=$x1+2;
                        if ($i<6){
                            $pdf->line(190,2+$y+$i*3,200,2+$y+$i*3);
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
                            $pdf->SetFont('Courier', 'B', 14);
                            if ($cd > 0){
                                $pdf->SetFont('Courier', 'B', 20);
                                $circle=url('/') . "/public/church/images/circle.png";
                                $pdf->Image($circle,$x+0.5,$y+2.5+3*$cd,2,2);
                                $pdf->SetFont('Courier', 'B', 14);
                            }
                            $pdf->SetFont('Courier', '', 7);
                        }
                        $x=$x+2;
                    }
                } else {
                    $pdf->SetTextColor(125,125,125);
                    $pdf->setxy(180,$y);
                    $pdf->SetFont('Courier', 'B', 10);
                    $pdf->cell(30,5,$chord,0,0,'C');            
                    $pdf->SetTextColor(0,0,0);
                    $pdf->SetDrawColor(125,125,125);
                    for ($i=1;$i<7;$i++){
                        $pdf->line($x1,$y+5,$x1,$y+17);
                        $x1=$x1+2;
                        if ($i<6){
                            $pdf->line(190,2+$y+$i*3,200,2+$y+$i*3);
                        }
                    }
                    $pdf->SetFillColor(0,0,0);
                }
                $y=$y+18;
            }
        }
        $filename=Str::slug($song->title, "-");
        $pdf->Output('I',$filename);
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
        $title = $venue->venue . " Bookings: " . date('j F',strtotime($days[1])) . " - " . date('j F Y',strtotime($days[7]));
        $pdf = new Fpdf;
        $pdf->SetFillColor(190,190,190);
        $pdf->AddPage('P');
        $pdf->SetTitle($title);
        $pdf->SetAutoPageBreak(true, 0);
        $pdf->SetFont('Arial', 'B', 22);
        $image=url('/') . "/public/church/images/colouredlogo.png";
        $pdf->Image($image,10,0,25,25);
        $pdf->text(40, 12, setting('general.church_name'));
        $pdf->SetFont('Arial', '', 16);
        $pdf->text(40, 20, $title);
        $pdf->SetFont('Arial', 'B', 12);
        $yy=40;
        foreach ($hours as $hh){
            if ($hh<>$hours[count($hours)-1]){
                $pdf->line(10,$yy-6,204,$yy-6);
                $pdf->text(13,$yy,$hh);
                $yy=$yy+17;
            }
        }
        $pdf->line(10,$yy-6,204,$yy-6);
        $pdf->line(10,34,10,289);
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
                $pdf->rect($xx-6,$sy,25,$ey-$sy,'DF');
                $pdf->setxy($xx-6,$sy+1);
                $pdf->SetFont('Arial', '', 8);
                if (($booking->diarisable_id) and (isset($booking->diarisable->tenant))){
                    $msg=$booking->diarisable->tenant;
                    if ($booking->details){
                        $msg.=" (" . $booking->details . ")";
                    }
                    $pdf->multicell(25,4,$msg,0,'C');
                } elseif (($booking->diarisable_id) and (isset($booking->diarisable->groupname))){
                    $msg=$booking->diarisable->groupname;
                    if ($booking->details){
                        $msg.=" (" . $booking->details . ")";
                    }
                    $pdf->multicell(25,4,$msg,0,'C');
                } elseif (($booking->diarisable_id) and (isset($booking->diarisable->event))){
                    $msg=$booking->diarisable->event;
                    if ($booking->details){
                        $msg.=" (" . $booking->details . ")";
                    }
                    $pdf->multicell(25,4,$msg,0,'C');
                }
                $pdf->SetFont('Arial', 'B', 12);
            }
            $pdf->line($xx-6,34,$xx-6,289);
            $pdf->text($xx,32,date('D j',strtotime($dd)));
            $xx=$xx+25;
        }
        $pdf->line($xx-6,34,$xx-6,289);
        $pdf->Output();
        exit;
    }

    
}
