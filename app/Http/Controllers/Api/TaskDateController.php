<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskDateRange;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TaskDateController extends Controller
{
    private $validFilterTypes = [
        'sevenDays',
        'thisMonth',
        'custom'
    ];
    
    public function index(Request $request)
    {
        $validatedData = $request->validate([
            'filterType' => [
                'required',
                Rule::in($this->validFilterTypes)
            ],
            'from' => 'required_if:filterType,custom',
            'to' => 'required_if:filterType,custom'
        ]);
        
        $filterType = $validatedData['filterType'];
        
        // list dates
                
        if ($filterType === 'sevenDays') {
            $from = (Carbon::now())->endOfDay();
            $to = (clone $from)->subDays(6) // because the current date is included
                ->startOfDay(); 
        } elseif ($filterType === 'thisMonth') {
            $from = (new Carbon('last day of this month'))->endOfDay();
            $to = (new Carbon('first day of this month'))->startOfDay();
        } else { // custom
            // swap because we show in DESC order from the latter date
            $from = Carbon::parse($validatedData['to'])->endOfDay();
            $to = Carbon::parse($validatedData['from'])->startOfDay();
        }
        
        $groupedTasks = Task::whereBetween('date', [$to, $from])
            ->groupBy('date')
            ->get([
                'date', DB::raw('COUNT(id) as count')
            ])->mapWithKeys(function ($item) {
                return [
                    $item['date'] => $item['count']
                ];
            })->toArray();
        
        // dd($groupedTasks);
                
        $dateRange = (new TaskDateRange())
            ->setFrom($from)
            ->setTo($to);
        
        return $dateRange->getDates()->map(function ($taskDate) use ($groupedTasks) {
            return [
                'date' => $taskDate,
                'count' => 
                    array_key_exists($taskDate, $groupedTasks) ? $groupedTasks[$taskDate] : 0
            ];
        });
    }
}
