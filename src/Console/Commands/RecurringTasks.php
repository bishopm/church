<?php

namespace Bishopm\Church\Console\Commands;

use Bishopm\Church\Mail\ChurchMail;
use Bishopm\Church\Models\Meetingtask;
use Illuminate\Console\Command;
use Bishopm\Church\Models\Recurringtask;
use Bishopm\Church\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
        // Remove completed tasks, except where they have a status note and are needed for meeting minutes
        DB::table('tasks')->where('status', 'done')->delete();
        DB::table('meetingtasks')->where('status', 'done')->update(['deleted_at' => Carbon::now()]);
        DB::table('meetingtasks')->where('status', 'done')->whereNull('statusnote')->delete();
        Log::info('Task clean up completed on ' . date('Y-m-d H:i'));

        // Send task reminders
        if (setting('automation.tasks_day') == date('w')){
            $tasks=Task::withWhereHas('individual')->whereIn('status',['todo','doing'])->get();
            foreach ($tasks as $task){
                if (!isset($data[$task->individual_id])){
                    $data[$task->individual_id]['indiv']=$task->individual;
                }
                $data[$task->individual_id]['tasks'][$task->status][]=['description'=>$task->description,'duedate'=>$task->duedate,'statusnote'=>''];
            }
            $meetingtasks=Meetingtask::withWhereHas('individual')->whereIn('status',['todo','doing'])->get();
            foreach ($meetingtasks as $mtask){
                if (!isset($data[$mtask->individual_id])){
                    $data[$mtask->individual_id]['indiv']=$mtask->individual;
                }
                $data[$mtask->individual_id]['tasks'][$mtask->status][]=['description'=>$mtask->description,'duedate'=>$mtask->duedate,'statusnote'=>$mtask->statusnote];
            }
            foreach ($data as $indiv){
                $msg = "Here's your weekly reminder email from " . setting('general.church_abbreviation') . " :) Please let us know if any of these items need to be changed, reassigned, updated or marked complete:<br>";
                foreach ($indiv['tasks'] as $key=>$tasktype){
                    if ($key=="todo"){
                        $msg.="<h4>To do</h4>";
                    } else {
                        $msg.="<h4>Progress</h4>";
                    } 
                    $msg.="<ul>";
                    foreach ($tasktype as $task){
                        $msg.="<li>" . $task['description'];
                        if ($task['duedate']){
                            $msg.=" (Due: " . $task['duedate'] . ")";
                        }
                        if ($key=="doing"){
                            $msg.=" <b>Current status:</b> " . $task['statusnote'];
                        }
                        $msg.="</li>";
                    }
                    $msg.="</ul>";
                }
                $msg.="<br>";
                $data=array();
                $data['firstname']=$indiv['indiv']->firstname;
                $data['subject']=setting('general.church_abbreviation') . " weekly reminder";
                $data['body']=$msg;
                $data['email']=$indiv['indiv']->email;
                Mail::to($data['email'])->queue(new ChurchMail($data));
            }
        }
    }
}