<?php

namespace Bishopm\Church\Console\Commands;

use Illuminate\Console\Command;
use Bishopm\Church\Models\Individual;
use Bishopm\Church\Models\Statistic;
use Bishopm\Church\Models\Group;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MonthlyMeasures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'church:monthlymeasures';

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
        Log::info('Preparing measures run on ' . date('Y-m-d H:i'));
        $reportmonth=date('Y-m',strtotime('-1 month'));
        $fquery=Group::with('individuals')->where('grouptype','fellowship')->get();
        $farray=array();
        foreach ($fquery as $fgroup){
            foreach ($fgroup->individuals as $indiv){
                $farray[$indiv->id]="yes";
            }
        }
        $fellowship=count($farray);
        DB::table('measures')->insert([
            'measuredate' => $reportmonth . '-01',
            'category' => 'connect',
            'measurement' => $fellowship
        ]);
        $squery=Group::with('individuals')->where('grouptype','service')->get();
        $sarray=array();
        foreach ($squery as $sgroup){
            foreach ($sgroup->individuals as $smember){
                $sarray[$smember->id]="yes";
            }
        }
        $service=count($sarray);
        DB::table('measures')->insert([
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
        DB::table('measures')->insert([
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
        DB::table('measures')->insert([
            'measuredate' => $reportmonth . '-01',
            'category' => 'worship',
            'measurement' => $worship
        ]);
        Log::info('Measures run completed on ' . date('Y-m-d H:i'));
    }
}
