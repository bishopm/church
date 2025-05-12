<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Formitem extends Model
{
    public $table = 'formitems';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function formitems(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }
}
