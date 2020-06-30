<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\Task as TaskResource;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Task::class);
    }
    
    /**
     * Display the user's task by date.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
        
        return TaskResource::collection(Task::where('date', $date)->get());
    }

    /**
     * Store a new task for a specific date.
     *
     * @param StoreTaskRequest $request
     * @return Response
     */
    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();
        
        if (!isset($validated['date'])) {
            $validated['date'] = Carbon::today()->format('Y-m-d');    
        }
        
        return Task::create($validated);
    }

    /**
     * Display the specified task.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return Task::findOrFail($id);
    }

    /**
     * Update the specified task.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        
        $task->update([
            $request->only('title', 'description', 'duration')
        ]);
        
        return $task;
    }

    /**
     * Remove the specified task.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        
        return $task->delete();
    }
}
