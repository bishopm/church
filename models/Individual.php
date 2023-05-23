<?php namespace Bishopm\Church\Models;

use Model;

/**
 * Model
 */
class Individual extends Model
{
    use \Winter\Storm\Database\Traits\Validation;
    
    use \Winter\Storm\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'bishopm_church_individuals';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
