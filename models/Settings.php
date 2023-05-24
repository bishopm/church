<?php namespace Bishopm\Church\Models;

use Model;
use Bishopm\Church\Models\Group;

class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'bishopm_church_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';

    public function getPastorsOptions($value, $formData)
    {
        $groups = Group::orderBy('groupname')->get();
        $data[0]="";
        foreach ($groups as $group){
            $data[$group->id]=$group->groupname;
        }
        return $data;
    }

    public function getBookshopgroupOptions($value, $formData)
    {
        $groups = Group::orderBy('groupname')->get();
        $data[0]="";
        foreach ($groups as $group){
            $data[$group->id]=$group->groupname;
        }
        return $data;
    }

    public function getBirthdaysgroupOptions($value, $formData)
    {
        $groups = Group::orderBy('groupname')->get();
        $data[0]="";
        foreach ($groups as $group){
            $data[$group->id]=$group->groupname;
        }
        return $data;
    }
    
    public function getTasksgroupOptions($value, $formData)
    {
        $groups = Group::orderBy('groupname')->get();
        $data[0]="";
        foreach ($groups as $group){
            $data[$group->id]=$group->groupname;
        }
        return $data;
    }
    
    public function getMaintenancegroupOptions($value, $formData)
    {
        $groups = Group::orderBy('groupname')->get();
        $data[0]="";
        foreach ($groups as $group){
            $data[$group->id]=$group->groupname;
        }
        return $data;
    }
}