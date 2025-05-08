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