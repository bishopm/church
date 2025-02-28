<?php

namespace Bishopm\Church\Console\Commands;

use Illuminate\Console\Command;
use Bishopm\Church\Models\Recurringtask;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RecurringTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'church:recurringtasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add recurring tasks';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $today=date('Y-m-d');
        $fulldate=date('Y-m-d H:i:s');
        // Weekly tasks
        $dayofweek = date('w');
        $wtasks=Recurringtask::where('frequency','weekly')->where('taskday',$dayofweek)->get();
        foreach ($wtasks as $wtask){
            DB::table('tasks')->insert([
                'duedate' => $today,
                'description' => $wtask->description,
                'individual_id' => $wtask->individual_id,
                'status' => 'todo',
                'created_at' => $fulldate,
                'updated_at' => $fulldate
            ]);
        }
        DB::table('tasks')->where('status', 'done')->delete();
        Log::info('Task clean up completed on ' . date('Y-m-d H:i'));
    }
}