<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ListTaskDatesRequest;
use App\Models\Task;
use App\Models\TaskDateRange;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TaskDateController extends Controller
{
    
    public function index(ListTaskDatesRequest $request)
    {
        $validated = $request->validated();
        
        $filterType = $validated['filterType'];
        
        // list dates
                
        if ($filterType === 'sevenDays') {
            $from = (Carbon::now())->endOfDay();
            // because the current date is included
            $to = (clone $from)->subDays(6) 
                ->startOfDay(); 
            
        } elseif ($filterType === 'thisMonth') {
            $from = (new Carbon('last day of this month'))->endOfDay();
            $to = (new Carbon('first day of this month'))->startOfDay();
            
        } else /*if ($filterType === 'custom')*/ {
            // swap because we show in DESC order from the latter date
            $from = Carbon::parse($validated['to'])->endOfDay();
            $to = Carbon::parse($validated['from'])->startOfDay();
        }
        
        $groupedTasks = Task::whereBetween('date', [$to, $from])
            ->where('user_id', auth()->id())
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
