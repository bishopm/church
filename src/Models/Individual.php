<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Individual extends Model
{
    protected $dates = ['deleted_at'];
    public $table = 'individuals';
    protected $guarded = ['id'];

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
    
    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function pastor(): HasOne
    {
        return $this->hasOne(Pastor::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class);
    }

    public function rosteritems(): BelongsToMany
    {
        return $this->belongsToMany(Rosteritem::class,'individual_rosteritem');
    }

    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' . $this->surname;
    }

    public function pastoralnotes(): MorphMany
    {
        return $this->morphMany(Pastoralnote::class,'pastoralnotable');
    }

    public function getLastseenAttribute() {
        $attend=Attendance::where('individual_id',$this->id)->orderBy('attendancedate','DESC')->first();
        if ($attend){
            return date('d M Y',strtotime($attend->attendancedate)) . " (" . $attend->service . ")";
        }
    }

    public function pastoralcases(): MorphMany
    {
        return $this->morphMany(Pastoralcase::class,'pastorable');
    }
}
