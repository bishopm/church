<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources\RosterResource\Pages;

use Bishopm\Church\Classes\BulksmsService;
use Bishopm\Church\Filament\Clusters\People\Resources\RosterResource;
use Bishopm\Church\Jobs\SendSMS;
use Bishopm\Church\Models\Group;
use Bishopm\Church\Models\Individual;
use Bishopm\Church\Models\Roster;
use Bishopm\Church\Models\Rostergroup;
use Bishopm\Church\Models\Rosteritem;
use Bishopm\Church\Models\Service;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\DB;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Illuminate\Support\HtmlString;
use Filament\Notifications\Notification;
use Bishopm\Church\Http\Controllers\ReportsController;
use Bishopm\Church\Mail\ChurchMail;
use Bishopm\Church\Models\Midweek;
use Bishopm\Church\Models\Plan;
use Bishopm\Church\Models\Video;
use Filament\Actions\ActionGroup;
use Illuminate\Support\Facades\Mail;

class ManageRoster extends Page implements HasForms
{
    use InteractsWithRecord, InteractsWithForms;

    protected static string $resource = RosterResource::class;

    protected static string $view = 'church::manage-roster';

    protected ?string $subheading = 'Subtitle';

    public ?array $data;

    public $rostermonth;

    public $count;

    public $credits, $smss;

    public function mount(int | string $record, $rostermonth): void
    {
        $this->record = $this->resolveRecord($record);
        $this->rostermonth = $rostermonth;
        $this->form->fill();
    }

    protected function getHeaderActions(): array
    {
        $schema = $this->constructPreviewSchema();
        if (intval(date('n')) & 1){
            $monthadd="+1 month";
            $rosterlabel = date('M') . ' and ' . date('M',strtotime($monthadd));
            $firstdate = date('Y-m-01');
            $nextrosterlabel = date('M', strtotime('+2 months')) . ' and ' . date('M',strtotime('+3 months'));
            $nextfirstdate = date('Y-m-01',strtotime('+2 months'));
        } else {
            $monthadd="-1 month";
            $rosterlabel = date('M',strtotime($monthadd)) . ' and ' . date('M');
            $firstdate = date('Y-m-01',strtotime('-1 month'));
            $nextrosterlabel = date('M',strtotime('+1 month')) . ' and ' . date('M',strtotime('+2 months'));
            $nextfirstdate = date('Y-m-01',strtotime('+1 month'));
        }
        return [
            Action::make('prev')
                ->action(fn () => self::changeMonth('prev'))
                ->icon('heroicon-m-backward')
                ->iconButton()
                ->url(function (Roster $record){
                    return route('filament.admin.people.resources.rosters.manage', [$record, date('Y-m-d',strtotime($this->rostermonth . ' - 1 month'))]);
                }),
            Action::make('next')
                ->action(fn () => self::changeMonth('next'))
                ->icon('heroicon-m-forward')
                ->iconButton()
                ->url(function (Roster $record){
                    return route('filament.admin.people.resources.rosters.manage', [$record, date('Y-m-d',strtotime($this->rostermonth . ' + 1 month'))]);
                }),
            ActionGroup::make([
                Action::make('emails')->label('Email ' . $rosterlabel . ' rosters')
                    ->requiresConfirmation()
                    ->action(fn () => self::sendRosterEmails($firstdate)),
                Action::make('nextemails')->label('Email ' . $nextrosterlabel . ' rosters')
                    ->requiresConfirmation()
                    ->action(fn () => self::sendRosterEmails($nextfirstdate))
                ])
                ->label('Email roster')
                ->icon('heroicon-m-envelope')
                ->color('primary')
                ->button(),
            Action::make('Preview messages')->label('Preview messages (' . date('j M',strtotime('next ' . $this->record->dayofweek)) . ')')
                ->modalHeading($this->record->roster . ": Preview messages")
                ->form($schema)
                ->modalSubmitActionLabel('Send messages')
                ->action(fn () => self::sendMessages()),
            Action::make('report')->label('View Roster')
                ->url(fn (): string => route('reports.roster', [
                    'id' => $this->record,
                    'year' => date('Y',strtotime($this->data['firstofmonth'])), 
                    'month' => date('m',strtotime($this->data['firstofmonth']))
                ]))
        ];
    }

    protected function constructPreviewSchema(){
        $record=$this->record;
        $rosterdate =date('Y-m-d',strtotime('next ' . $record->dayofweek));
        $schema=[Placeholder::make('PreviewDate')->content(new HtmlString('<b>' . date('l d F Y',strtotime($rosterdate)) . '</b>'))->label('')];
        $rosteritems = Rosteritem::with('individuals','rostergroup.group')->where('rosterdate',$rosterdate)->whereHas('rostergroup', function ($q) use ($record) {
            $q->where('roster_id',$record->id);
        })->get();
        $data['ridata']=array();
        $messages = "";
        foreach ($rosteritems as $ri){
            foreach ($ri->individuals as $indiv){
                if ($indiv->cellphone){
                    $msg = $indiv->firstname . ", " . $record->message . " (" . $ri->rostergroup->group->groupname . ")";
                    if ($ri->rostergroup->extrainfo=="reading"){
                        $reading = Service::where('servicedate',$rosterdate)->where('servicetime',$record->sundayservice)->first();
                        if (isset($reading->reading)){
                            $msg = $msg . " Reading: " . $reading->reading;
                        }
                    }
                    $this->data['ridata'][$ri->rostergroup->group->groupname][$indiv->cellphone]=$msg;
                    $this->data['allmessages'][$indiv->cellphone]=$msg;
                    $messages = $messages . $indiv->cellphone . ": " . $msg . "<br>";
                }
            }
        }
        $schema[] = Placeholder::make('Bulksms Credits')->label('')->content(function (){
            $this->smss = new BulksmsService(setting('services.bulksms_clientid'), setting('services.bulksms_api_secret'));
            $this->credits = $this->smss->get_credits();
            return "Available BulkSMS credits: " . $this->credits;
        });
        $schema[] = Placeholder::make('Preview')->content(new HtmlString($messages))->label('');
        return $schema;
    }

    public function sendMessages() {
        if ($this->credits >= count($this->data['allmessages'])) {
            SendSMS::dispatch($this->data['allmessages']);
            if (count($this->data['allmessages']) > 1){
                Notification::make('SMS sent')->title('SMSes sent to ' . count($this->data['allmessages']) . ' individuals')->send();
            } elseif (count($this->data['allmessages'])==1) {
                Notification::make('SMS sent')->title('SMS sent to 1 individual')->send();
            }
        } else {
            Notification::make('failurenote')->title('Insufficient credits - please top up your BulkSMS account')->send();
        }
    }
    
    public function sendRosterEmails($firstday)
    {
        $firstday = date('Y-m-d', strtotime($firstday));
        $nextmonth = date('Y-m-t', strtotime($firstday. ' + 1 month'));
        $repcontroller=new ReportsController();
        $makepdf=$repcontroller->roster($this->record->id,date('Y',strtotime($firstday)),date('n',strtotime($firstday)),2,'F');
        $groups = Rostergroup::with('group.individuals')->where('roster_id',$this->record->id)->get();
        $rost = Roster::find($this->record->id);
        $indivs = array();
        foreach ($groups as $group){
            if (isset($group->group)){
                foreach ($group->group->individuals as $member){
                    if (!isset($indivs[$member->id])){
                        $indivs[$member->id]=array();
                    }
                    $indivs[$member->id][]=['group_id'=>$group->group->id,'groupname'=>$group->group->groupname,'videos'=>$group->videos];
                }
            }
        }
        $emailcount=0;
        $rostertitle=date('F',strtotime($firstday)) . " and " . date('F Y',strtotime($nextmonth));
        foreach ($indivs as $id=>$indiv){
            $person = Individual::find($id);
            $message = "Thank you so much for your willingness to serve at WMC. Attached is the service roster for " . $rostertitle . ".\n";
            foreach ($indiv as $group){
                $message.="\n**" . $group['groupname'] . "**\n\n";
                $message.="If you are not able to serve on any given day, please contact one of the other members of the team below and arrange to swap duties:\n\n";
                $team = Group::with('individuals')->where('id',$group['group_id'])->first();
                foreach ($team->individuals as $tm){
                    if ($tm->id <> $id){
                        $message.=$tm->firstname . " " . $tm->surname . " (" . $tm->cellphone . ")\n\n";
                    }
                }
                if ($group['videos']) {
                    $videos=Video::whereIn('id',json_decode($group['videos']))->get();
                    if (count($videos)>1){
                        $add="s";
                    } else {
                        $add="";
                    }
                    $message.="\nIf you are new to the team, or have not seen it yet, have a look at the following training video" . $add . " we have prepared for this team. We hope this is helpful - please share any feedback you may have to help us improve!\n\n";
                    foreach ($videos as $video) {
                        $message.="* [" . $video->title . "](" . $video->url . ")\n";
                    }
                }
            }
            $message.="\nMay God bless you as you serve him here. Thank you!";
            $data=array();
            $data['body'] = $message;
            $data['subject'] = $rostertitle . " Roster: " . $rost->roster;
            $data['firstname'] = $person->firstname;
            $data['url'] = "https://westvillemethodist.co.za";
            $data['firstname'] = $person->firstname;
            $data['attachdata']=base64_encode($makepdf);
            $data['attachname']=str_replace(" ","_",$data['subject']) . ".pdf";
            $emailcount++;
            Mail::to($person->email)->queue(new ChurchMail($data));
            Notification::make('Email sent')->title('Emails sent: ' . $emailcount)->send();
        }
        return "Emails sent: " . $emailcount;
    }

    /*protected function changeMonth($start){
        if ($start=="prev"){
            $this->data['firstofmonth'] = date('Y-m-d', strtotime($this->data['firstofmonth'] . " -1 month"));
        } else {
            $this->data['firstofmonth'] = date('Y-m-d', strtotime($this->data['firstofmonth'] . " +1 month"));
        }        
        $weeks = $this->getWeeks($this->data['firstofmonth']);
        foreach ($weeks as $n=>$week){
            $wkvar = 'week'.$n;
            $this->data[$wkvar]=$week;
        }
        foreach ($this->data['rgs'] as $rg){
            foreach ($weeks as $w=>$wk){
                $vv = 'select_' . $w . "_" . $rg;
                $this->data[$vv] = $this->getIndivs($rg,$wk);
            }
        }
    }*/

    protected function getWeeks($firstofmonth)
    {
        $thismonth=date('Y-m',strtotime($firstofmonth));
        $this->data['prev']=date('M Y',strtotime($thismonth . '-01 -1 month'));
        $this->data['next']=date('M Y',strtotime($thismonth . '-01 +1 month'));
        for ($i=1;$i<=7;$i++){
            if (date('l',strtotime($thismonth . "-" . $i)) == $this->record->dayofweek) {
                $weeks[]=date('Y-m-d',strtotime($thismonth . "-" . $i));
            }
        }
        for ($j=1;$j<=4;$j++){
            if ($thismonth==date('Y-m',strtotime($weeks[0] . ' + ' . $j . ' week'))){
                $weeks[]=date('Y-m-d',strtotime($weeks[0] . ' + ' . $j . ' week'));
            }
        }

        // Deal with midweek services and check if potential services have preachers before adding to this roster
        $servicetime=str_replace("h",":",$this->record->sundayservice);
        $service=DB::connection('methodist')->table('services')->where('society_id',setting('services.society_id'))->where('servicetime',$servicetime)->first();
        $midweeks=Midweek::where('servicedate','>=',$firstofmonth)->where('servicedate','<',date('Y-m-d',strtotime($firstofmonth . ' + 1 month')))->get();
        if (count($midweeks)){
            foreach ($midweeks as $mw){
                if ($service){
                    $plan=Plan::where('servicedate',$mw->servicedate)->where('service_id',$service->id)->get();
                    if (count($plan)){
                        $weeks[]=$mw->servicedate;
                    }
                }
            }
        }
        asort($weeks);
        $this->data['columns']=1+count($weeks);
        return array_values($weeks);
    }

    private static function updateIndivs($state, $rosterdate, $rostergroup){
        $ri = Rosteritem::where('rosterdate',$rosterdate)->where('rostergroup_id',$rostergroup)->first();
        if (isset($ri->id)){
            DB::table('individual_rosteritem')->where('rosteritem_id',$ri->id)->delete();
        } else {
            $ri = Rosteritem::create([
                'rostergroup_id' => $rostergroup,
                'rosterdate' => $rosterdate
            ]);
        }
        if (is_array($state)){
            foreach ($state as $indiv){
                DB::table('individual_rosteritem')->insert([
                    'individual_id' => $indiv,
                    'rosteritem_id' => $ri->id
                ]);
            }
        } else {
            if (isset($state)){
                DB::table('individual_rosteritem')->insert([
                    'individual_id' => $state,
                    'rosteritem_id' => $ri->id
                ]);
            }
        }
    }

    public function getIndivs($rg, $wk){
        $rosteritem=Rosteritem::with('individuals')->where('rosterdate',$wk)->where('rostergroup_id',$rg)->first();
        $ridat=array();
        if ($rosteritem){
            if (isset($rosteritem->individuals)){
                foreach ($rosteritem->individuals as $indiv){
                    $ridat[]=$indiv->id;
                }
            }
        }
        return $ridat;
    }

    public function form(Form $form): Form
    {
        $schema = array();
        $roster=Roster::find($this->record->id);
        if (!isset($this->data['firstofmonth'])){
            $this->data['firstofmonth'] = date('Y-m-01',strtotime($this->rostermonth));
        }
        $this->subheading = $this->record->roster;
        $rostergroups = Rostergroup::with('group.individuals')->where('roster_id',$this->record->id)->get()->sortBy('group.groupname');
        $weeks=$this->getWeeks($this->data['firstofmonth']);
        $rosterday=Roster::find($this->record->id)->dayofweek;
        foreach ($weeks as $ndx=>$label){
            if ($ndx==0){
                $schema[] = Placeholder::make('blank')->label('');
            }
            $this->data['week' . $ndx] = $label;
            if (date('l',strtotime($label))<>$rosterday){
                $label.= " (" . date('D',strtotime($label)) . ")";
            }
            $schema[] = TextInput::make('week' . $ndx)->label('')
                            ->live()
                            ->default($label)
                            ->readonly();
        }
        foreach ($rostergroups as $ndx=>$rg) {
            $this->data['rgs'][]=$rg->id;
            $schema[] = Placeholder::make($rg->group->groupname)->content($rg->group->groupname)->label('');
            $members=[];
            foreach ($rg->group->individuals as $indiv){
                if ($roster->sundayservice){
                    if (($indiv->pivot->categories) and (in_array($roster->sundayservice,json_decode($indiv->pivot->categories)))){
                        $members[$indiv->id] = $indiv->firstname . " " . $indiv->surname;        
                    }
                } else {
                    $members[$indiv->id] = $indiv->firstname . " " . $indiv->surname;
                }
            }
            foreach ($weeks as $wno=>$week){
                $onduty=array();
                $onduty=$this->getIndivs($rg->id,$week);
                if ($rg->maxpeople==1){
                    if (isset($onduty)){
                        $ind = $onduty;
                    } else {
                        $ind=0;
                    }
                    $schema[] = Select::make('select_' . $wno . "_" . $rg->id)
                        ->label('')
                        ->options($members)
                        ->default($ind)
                        ->live()
                        ->placeholder('')
                        ->afterStateUpdated(fn ($state) => self::updateIndivs($state, $week, $rg->id));
                } else {
                    if (isset($onduty)){
                        $ind = $onduty;
                    } else {
                        $ind=[];
                    }
                    $schema[] = Select::make('select_' . $wno . "_" . $rg->id)
                        ->label('')
                        ->multiple()
                        ->options($members)
                        ->maxItems($rg->maxpeople)
                        ->default($ind)
                        ->live()
                        ->placeholder('')
                        ->afterStateUpdated(fn ($state) => self::updateIndivs($state, $week, $rg->id));
                }
            }
        }
        return $form
            ->schema($schema)
            ->columns($this->data['columns'])
            ->statePath('data');
    }

}