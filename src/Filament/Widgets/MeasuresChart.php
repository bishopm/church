<?php

namespace Bishopm\Church\Filament\Widgets;

use Bishopm\Church\Models\Individual;
use Bishopm\Church\Models\Measure;
use Filament\Widgets\ChartWidget;

class MeasuresChart extends ChartWidget
{
    protected static ?string $heading = 'Growth measures';

    public function getDescription(): ?string
    {
        $total = Individual::count();
        $withGroupLeader = Individual::whereNotNull('groupleader')->count();
        $percentage = $total > 0 ? round(($withGroupLeader / $total) * 100, 1) : 0;

        return "{$percentage}% of individuals have a group leader (" . $withGroupLeader . "/" . $total . ")";
    }

    protected function getData(): array
    {
        $data=array();
        $lastyear=date('Y-m-01',strtotime('-11 months'));
        $measures=Measure::where('measuredate','>',$lastyear)->orderBy('measuredate','ASC')->get();
        foreach ($measures as $measure){
            $data[$measure->category][]=$measure->measurement;
            if ($measure->category=="worship"){
                $data['labels'][]=date('M',strtotime($measure->measuredate));
            }
        }
        if (count($data)){
            return [
                'datasets' => [
                    [
                        'label' => 'Serve',
                        'data' => $data['serve'],
                        'borderColor' => 'blue'
                    ],
                    [
                        'label' => 'Worship',
                        'data' => $data['worship'],
                        'borderColor' => 'red'
                    ],
                    [
                        'label' => 'Connect',
                        'data' => $data['connect'],
                        'borderColor' => 'green'
                    ],
                    [
                        'label' => 'Give',
                        'data' => $data['give'],
                        'borderColor' => 'orange'
                    ]
                ],
                'labels' => $data['labels'],
            ];
        }
    }

    protected function getType(): string
    {
        return 'line';
    }

    public static function canView(): bool 
    { 
        $roles =auth()->user()->roles->toArray(); 
        $permitted = array('Office','Finance');
        foreach ($roles as $role){
            if ((in_array($role['name'],$permitted)) or (auth()->user()->isSuperAdmin())){
                return true;
            }
        }
        return false;
    }
}
