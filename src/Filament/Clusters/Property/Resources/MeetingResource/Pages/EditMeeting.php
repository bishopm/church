<?php

namespace Bishopm\Church\Filament\Clusters\Property\Resources\MeetingResource\Pages;

use Bishopm\Church\Filament\Clusters\Property\Resources\MeetingResource;
use Bishopm\Church\Http\Controllers\ReportsController;
use Bishopm\Church\Mail\ChurchMail;
use Bishopm\Church\Models\Group;
use Bishopm\Church\Models\Individual;
use Bishopm\Church\Models\Meeting;
use Filament\Actions;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;

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

    public function sendMinutes($form){
        $data['body']=$form['message'];
        $report=new ReportsController();
        $meeting=Meeting::find($this->record->id);
        $data['subject']=$meeting->details . " minutes (" . date('j M Y',strtotime($meeting->meetingdatetime)) . ")";
        $recipients = Group::with('individuals')->where('id',$meeting->group_id)->first();
        $data['attachdata']=base64_encode($report->minutes($this->record->id,true));
        $data['attachname']="minutes_" . date('ymd',strtotime($meeting->meetingdatetime)) . ".pdf";
        $count=0;
        $secretary=Individual::find(setting('admin.church_secretary'));
        $cc=false;
        foreach ($recipients->individuals as $indiv){
            $data['firstname'] = $indiv->firstname;
            if ($indiv->email){
                if ($indiv->email==$secretary->email){
                    $cc=true;
                }
                Mail::to($indiv->email)->send(new ChurchMail($data));
                $count++;
            }
        }
        if ((!$cc) and ($secretary)){
            $data['firstname'] = $secretary->firstname;
            $data['subject'] = "FYI: " . $data['subject'];
            Mail::to($indiv->email)->send(new ChurchMail($data));
        }
        if ($count>1){
            Notification::make('Email sent')->title('Email sent to ' . $count . ' recipients.')->send();
        } elseif ($count==1) {
            Notification::make('Email sent')->title('Email sent to 1 recipient.')->send();
        }
        return $count;
    }
}
