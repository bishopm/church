<?php

namespace Bishopm\Churchsite\Console;

use Illuminate\Console\Command;
use Bishopm\Churchsite\Models\Individual;
use Bishopm\Churchsite\Models\Statistic;
use Bishopm\Churchsite\Models\Group;
use Bishopm\Churchsite\Models\Settings;
use DB;

class MonthlyMeasures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'churchsite:monthlymeasures';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store monthly stats';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $reportmonth=date('Y-m',strtotime('-1 month'));
        $fquery=Group::with('groupmembers')->where('grouptype','fellowship')->get();
        $farray=array();
        foreach ($fquery as $fgroup){
            foreach ($fgroup->groupmembers as $fmember){
                if (isset($fmember->individual)){
                    $farray[$fmember->individual->id]="yes";
                }
            }
        }
        $fellowship=count($farray);
        DB::table('bishopm_churchsite_measures')->insert([
            'measuredate' => $reportmonth . '-01',
            'category' => 'connect',
            'measurement' => $fellowship
        ]);
        $squery=Group::with('groupmembers')->where('grouptype','service')->get();
        $sarray=array();
        foreach ($squery as $sgroup){
            foreach ($sgroup->groupmembers as $smember){
                if (isset($smember->individual)){
                    $sarray[$smember->individual->id]="yes";
                }
            }
        }
        $service=count($sarray);
        DB::table('bishopm_churchsite_measures')->insert([
            'measuredate' => $reportmonth . '-01',
            'category' => 'serve',
            'measurement' => $service
        ]);
        $gquery=Individual::where('giving','>',0)->get();
        $garray=array();
        foreach ($gquery as $gmember){
            $garray[$gmember->giving]="yes";
        }
        $giving=count($garray);
        DB::table('bishopm_churchsite_measures')->insert([
            'measuredate' => $reportmonth . '-01',
            'category' => 'give',
            'measurement' => $giving
        ]);
        $wquery=Statistic::where(DB::raw("SUBSTR(statdate, 1, 7)"),$reportmonth)->get();
        $warray=array();
        $total=0;
        foreach ($wquery as $stat){
            if (isset($warray[$stat->statdate])){
                $warray[$stat->statdate]=$warray[$stat->statdate]+$stat->attendance;
            } else {
                $warray[$stat->statdate]=$stat->attendance;
            }
            $total=$total+$stat->attendance;
        }
        $worship=round($total/count($warray),0);
        DB::table('bishopm_churchsite_measures')->insert([
            'measuredate' => $reportmonth . '-01',
            'category' => 'worship',
            'measurement' => $worship
        ]);
    }
}
