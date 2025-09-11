<?php

namespace Bishopm\Church\Filament\Clusters\Worship\Resources\ServiceResource\Pages;

use Bishopm\Church\Filament\Clusters\Worship\Resources\ServiceResource;
use Bishopm\Church\Models\Individual;
use Bishopm\Church\Models\Service;
use Bishopm\Church\Models\Setitem;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;

class EditService extends EditRecord
{
    protected static string $resource = ServiceResource::class;

    public $indiv, $notifyLabel, $services;

    protected function getHeaderActions(): array
    {
        $this->indiv = Individual::find(setting('admin.church_secretary'));
        if ($this->indiv){
            $this->notifyLabel=$this->indiv->firstname;
        } else {
            $this->notifyLabel="Office";
        }
        return [
            Actions\Action::make('Copy service')
                ->disabled(function () {
                    $setting=setting('general.services');
                    $servicetimes=Service::where('servicedate',$this->record->servicedate)->get()->pluck('servicetime')->toArray();
                    $services=array_diff($setting, $servicetimes);
                    if (!count($services)){
                        return true;
                    } else {
                        foreach ($services as $ss){
                            $this->services[$ss]=$ss;
                        }
                    }
                })
                ->form([
                    Select::make('service')->label('Create duplicate service at this time')
                        ->options($this->services)
                ])
                ->action(function (array $data) {
                    self::copyService($data);
                }),
            Actions\Action::make('Notify ' . $this->notifyLabel)->label('Notify ' . $this->notifyLabel)->action(function () {
                if ($this->indiv){
                    $email=$this->indiv->email;
                    $fname=" " . $this->indiv->firstname . "!";
                } else {
                    $email=setting('email.church_email');
                    $fname="!";
                }
                $subject = 'New service: ' . $this->record->servicetime . " " . $this->record->servicedate;
                $body = "Hi" . $fname . "<br><br>Just to let you know that a new service has been added to the database.<br><br>It can be accessed <a href=\"" . url('/') . "/admin/worship/services/" . $this->record->id . "/edit\">here</a><br><br>Thank you!";
                Mail::html($body, function ($message) use ($email, $subject) {
                    $message->to($email)->subject($subject);
                    $message->from(setting('email.church_email'),setting('general.church_name'));
                });
                Notification::make('Email sent')->title('Email sent to ' . $email)->send();
            }),
            Actions\Action::make('Order of service')->url(fn (): string => route('reports.service', ['id' => $this->record])),
            $this->getSaveFormAction()->formId('form'),
            Actions\DeleteAction::make()
                ->before(function () {
                    $setitems = Setitem::where('service_id',$this->record->id)->delete();
                })
        ];
    }

    private function copyService($data){
        $set=Service::with(['setitems' => function($q) { $q->orderBy('sortorder', 'asc'); }])->where('id',$this->record->id)->first();
        $newset = Service::create([
            'servicedate'=>$set->servicedate,
            'servicetime'=>$data['service'],
            'servicetitle'=>$set->servicedate . ' (' . $data['service'] . ')',
            'reading'=>$set->reading,
            'series_id'=>$set->series_id
        ]);
        foreach ($set->setitems as $item){
            $newitem=new Setitem([
                'service_id' => $item->service_id,
                'setitemable_id' => $item->setitemable_id,
                'setitemable_type' => $item->setitemable_type,
                'sortorder' => $item->sortorder,
                'note' => $item->note
            ]);
            $newset->setitems()->save($newitem);
        }
        Notification::make('Service created')->title('Duplicate set has been created at ' . $data['service'])->send();
        return redirect()->route('filament.admin.worship.resources.services.edit', ['record' => $newset->id]);
    }
}
