<?php namespace Bishopm\Church\FormWidgets;

use Backend\Classes\FormWidgetBase;
use Config;

class Map extends FormWidgetBase
{
    public $accesstoken = '';
    
    protected $defaultAlias = 'map';

    public function render()
    {
        //$this->vars['latitude'] = $this->getLatitude();
        //$this->vars['longitude'] = $this->getLongitude();
        return $this->makePartial('mymap');
    }
    
    public function init()
    {
        $this->addCss('/plugins/bishopm/church/assets/css/leaflet.css');
        $this->addJs('/plugins/bishopm/church/assets/js/leaflet.js');
        $this->fillFromConfig([
            'accesstoken'
        ]);
        parent::init();
    }
}