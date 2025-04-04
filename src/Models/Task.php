<?php

namespace Bishopm\Church\Models;

use Bishopm\Church\Traits\Taggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use Taggable;
    use SoftDeletes;

    public $table = 'tasks';
    protected $guarded = ['id'];

    protected static function booted(): void
    {
        self::updating(static function (Task $task): void {
            if (($task->status=="done") and ($task->agendaitem_id)){
                $task->statusnote="Completed on " . date('Y-m-d');
            }
        });
    }

    public function individual(): BelongsTo
    {
        return $this->belongsTo(Individual::class);
    }
}
