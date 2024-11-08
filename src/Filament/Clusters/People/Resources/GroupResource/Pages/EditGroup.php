<?php

namespace Bishopm\Church\Filament\Clusters\People\Resources\GroupResource\Pages;

use Bishopm\Church\Filament\Clusters\People\Resources\GroupResource;
use Bishopm\Church\Mail\ChurchMail;
use Bishopm\Church\Models\Group;
use Bishopm\Church\Classes\BulksmsService;
use Bishopm\Church\Jobs\SendSMS;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;

class EditGroup extends EditRecord
{
    protected static string $resource = GroupResource::class;

    public ?array $data;

    protected function getHeaderActions(): array
    {

        return [
            Actions\Action::make('Group SMS')->label('Group SMS')
                ->form([
                    Placeholder::make('Credits')->content(function (){
                        $smss = new BulksmsService(setting('services.bulksms_clientid'), setting('services.bulksms_api_secret'));
                        return "Available credits: " . $smss->get_credits(setting('services.bulksms_clientid'), setting('services.bulksms_api_secret'));
                    }),
                    Textarea::make('message')
                ])
                ->action(function (array $data) {
                    self::sendSMS($data);
                }),
            Actions\Action::make('Group email')->label('Group email')
                ->form([
                    TextInput::make('subject'),
                    FileUpload::make('attachment'),
                    MarkdownEditor::make('body')
                ])
                ->action(function (array $data) {
                    self::sendEmail($data);
                }),
            Actions\Action::make('Group report')->url(fn (): string => route('reports.group', ['id' => $this->record])),
            Actions\DeleteAction::make(),
        ];
    }

    public function sendEmail($data){
        $group=Group::with('individuals')->where('id',$this->record->id)->first();
        $data['url'] = "https://westvillemethodist.co.za";
        $count=0;
        foreach ($group->individuals as $indiv){
            $data['firstname'] = $indiv['firstname'];
            if ($indiv['email']){
                Mail::to($indiv['email'])->queue(new ChurchMail($data));
                $count++;
            }
        }
        if ($count > 1){
            Notification::make('Email sent')->title('Email sent to ' . $count . ' individuals')->send();
        } elseif ($count==1) {
            Notification::make('Email sent')->title('Email sent to 1 individual')->send();
        }
    }

    public function sendSMS($data){
        $messages = array();
        $smss = new BulksmsService(setting('services.bulksms_clientid'), setting('services.bulksms_api_secret'));
        $credits = $smss->get_credits();
        $group=Group::with('individuals')->where('id',$this->record->id)->first();
        foreach ($group->individuals as $indiv){
            if ($indiv->cellphone){
                $messages[$indiv->cellphone] = "Hi " . $indiv['firstname'] . ". " . $data['message'];
            }
        }
        if (count($messages)){
            if ($credits >= count($messages)) {
                SendSMS::dispatch($messages);
                if (count($messages) > 1){
                    Notification::make('SMS sent')->title('SMSes sent to ' . $count . ' individuals')->send();
                } elseif (count($messages)==1) {
                    Notification::make('SMS sent')->title('SMS sent to 1 individual')->send();
                }
            } else {
                Notification::make('failurenote')->title('Insufficient credits - please top up your BulkSMS account')->send();
            }
        } else {
            Notification::make('failurenote')->title('No messages were sent - check that group members have valid cellphone numbers')->send();
        }
    }

}
