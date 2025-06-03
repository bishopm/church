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

    public function getDetailsAttribute() {
        $props=json_decode($this->itemdata);
        if ($this->itemtype=="line"){
            return "Line";
        } elseif ($this->itemtype=="cell"){
            return "Text box: " . $props->text;
        } elseif ($this->itemtype=="image"){
            return "Image: " . $props->file;
        } elseif ($this->itemtype=="text"){
            return "Text: " . $props->text;
        }
    }

    public function getXyAttribute() {
        $props=json_decode($this->itemdata);
        if ($this->itemtype=="line"){
            return $props->x . "," . $props->y;
        } elseif ($this->itemtype=="cell"){
            return $props->x . "," . $props->y;
        } elseif ($this->itemtype=="image"){
            return $props->x . "," . $props->y;
        } elseif ($this->itemtype=="text"){
            return $props->x . "," . $props->y;
        }
    }

}
