<?php
 
namespace Bishopm\Church\Livewire;

use Bishopm\Church\Models\Individual;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Appsettings extends Component
{
    public $settings = [];
    public $id;

    public function mount(){
        $allsettings=[
            'Bible in a year' => false,
            'Faith for daily living' => false,
            'Methodist prayer' => false,
            'Quiet moments' => false,
            'Login' => date('Y-m-d')
        ];
        foreach ($allsettings as $thiskey=>$thissetting){
            if (!in_array($thiskey,$this->settings)){
                $this->settings[$thiskey]=$thissetting;
            }
        }
        ksort($this->settings);
        $this->savetoDb();
    }

    private function savetoDb(){
        $indiv=Individual::find($this->id);
        $indiv->app=$this->settings;
        $indiv->save();
        $member=Config::get('member');
        $member['app']=$indiv->app;
        Config::set('member',$member);
    }

    public function updateSettings(){
        $this->savetoDb();
    }

    public function render()
    {
        return view('church::livewire.appsettings');
    }
}