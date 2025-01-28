<?php

namespace Bishopm\Church\Filament\Clusters\Property\Resources\MeetingResource\Pages;

use Bishopm\Church\Filament\Clusters\Property\Resources\MeetingResource;
use Bishopm\Church\Http\Controllers\ReportsController;
use Bishopm\Church\Models\Group;
use Bishopm\Church\Models\Meeting;
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
        $message=$data['message'];
        $report=new ReportsController();
        $id=$this->record->id;
        $meeting=Meeting::find($id);
        $recipients = Group::with('individuals')->where('id',$meeting->group_id)->first();
        $minutespdf=$report->minutes($id);
        $indivs = array();
        foreach ($indivs as $id=>$indiv){
            Mail::send('bishopm.churchsite::mail.general', $dat, function ($msg) use ($dat,$minutespdf) {
                $msg->from(Settings::get('smtpemail'), Settings::get('church_name'));
                $msg->subject($dat['title']);
                $msg->to($dat['email']);
                $msg->attachData($minutespdf, 'minutes.pdf', ['mime' => 'application/pdf']);
            });
        }
        return "Emails sent: " . $emailcount;
    }
}
