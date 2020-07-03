<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExportTasksRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExportController extends Controller
{
    public function index(ExportTasksRequest $request)
    {
        $validated = $request->validated();
        
        $range = [
            $validated['from'],
            $validated['to']
        ];

        $tasksByDate = Task::whereBetween('date', $range)
            ->where('user_id', auth()->id())
            ->get([
                'title', 'description', 'duration', 'user_id', 'date'
            ])
            ->groupBy('date')
            ->map(function ($tasks) {
                $date = [];
                $date['tasks'] = $tasks;
                $date['total'] = $tasks->sum('duration');
                return $date;
            })->toArray();
        
        ksort($tasksByDate); // key sort (by date)
        
        // dd($tasksByDate);
        
        return view('export', compact('tasksByDate'));
    }
}
