<?php

namespace Bishopm\Church\Filament\Clusters\Property\Resources\MeetingResource\Pages;

use Bishopm\Church\Filament\Clusters\Property\Resources\MeetingResource;
use Bishopm\Church\Http\Controllers\ReportsController;
use Filament\Actions;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\EditRecord;

class EditMeeting extends EditRecord
{
    protected static string $resource = MeetingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Email minutes to group')
                ->form([
                    Textarea::make('message')
                ])
                ->action(function (array $data) {
                    self::sendMinutes($data);
                }),
            Actions\Action::make('Minutes')->url(fn (): string => route('reports.minutes', ['id' => $this->record])),
            Actions\Action::make('A4 agenda')->url(fn (): string => route('reports.a4meeting', ['id' => $this->record])),
            Actions\Action::make('A5 agenda')->url(fn (): string => route('reports.a5meeting', ['id' => $this->record])),
            Actions\DeleteAction::make(),
        ];
    }

    public function sendMinutes($data){
        //dd($data);
        $report=new ReportsController();
        $minutespdf=$report->minutes(1);
        $recipients = Group::with('individuals')->where('id',$roster)->get();
        $rost = Rostermodel::find($roster);
        $indivs = array();
        foreach ($groups as $group){
            if (isset($group->group)){
                foreach ($group->group->groupmembers as $member){
                    if (!isset($indivs[$member->individual_id])){
                        $indivs[$member->individual_id]=array();
                    }
                    $indivs[$member->individual_id][]=['group_id'=>$group->group->id,'groupname'=>$group->group->groupname,'video'=>$group->video];
                }
            }
        }
        $emailcount=0;
        $rostertitle=date('F',strtotime($firstday)) . " and " . date('F Y',strtotime($nextmonth));
        foreach ($indivs as $id=>$indiv){
            $person = Individual::find($id);
            $message = "Thank you so much for your willingness to serve at WMC. Attached is the service roster for " . $rostertitle . ".<br>";
            foreach ($indiv as $group){
                $message.="<br><b>" . $group['groupname'] . "</b><br>";
                $message.="If you are not able to serve on any given day, please contact one of the other members of the team below and arrange to swap duties:<br><br>";
                $team = Group::with('groupmembers.individual')->where('id',$group['group_id'])->first();
                foreach ($team->groupmembers as $tm){
                    if ($tm->individual->id <> $id){
                        $message.=$tm->individual->firstname . " " . $tm->individual->surname . " (" . $tm->individual->cellphone . ")<br>";
                    }
                }
                if ($group['video']) {
                    $message.="<br>If you are new to the team, or have not seen it yet, have a look at the <b><a href=\"" . $group['video'] . "\">training video</a></b> we have prepared for this team. We hope it is helpful - please share any feedback you may have to help us improve!";
                }
            }
            $message.="<br>May God bless you as you serve him here. Thank you!";
            if ($person->email){
                $dat=array();
                $dat['content'] = $message;
                $dat['title'] = $rost->roster . ": " . $rostertitle . " Roster";
                $dat['church'] = Settings::get('church_name');
                $dat['sender'] = Settings::get('smtpemail');
                $dat['firstname'] = $person->firstname;
                $dat['surname'] = $person->surname;
                $dat['email'] = $person->email;
                $emailcount++;
                Mail::send('bishopm.churchsite::mail.general', $dat, function ($msg) use ($dat,$rosterpdf) {
                    $msg->from(Settings::get('smtpemail'), Settings::get('church_name'));
                    $msg->subject($dat['title']);
                    $msg->to($dat['email']);
                    $msg->attachData($rosterpdf, 'WMC_roster.pdf', ['mime' => 'application/pdf']);
                });
            }
        }
        return "Emails sent: " . $emailcount;
    }
}
