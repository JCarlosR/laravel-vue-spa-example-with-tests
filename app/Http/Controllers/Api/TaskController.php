<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\Task as TaskResource;
use App\Models\Task;
use Carbon\Carbon;
use Exception;
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
        
        return TaskResource::collection(
            Task::where('date', $date)->where('user_id', auth()->id())->get()
        );
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
     * @param Task $task
     * @return Task
     */
    public function show(Task $task)
    {
        return $task;
    }

    /**
     * Update the specified task.
     *
     * @param Request $request
     * @param Task $task
     * @return Task
     */
    public function update(Request $request, Task $task)
    {        
        $task->update(
            $request->only('title', 'description', 'duration')
        );
        
        return $task;
    }

    /**
     * Remove the specified task.
     *
     * @param Task $task
     * @return bool|null
     * @throws Exception
     */
    public function destroy(Task $task)
    {        
        return $task->delete();
    }
}
