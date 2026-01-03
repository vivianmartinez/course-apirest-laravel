<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks with their users.
     */
    public function index()
    {
        $tasks = Task::with('user')->getOrPaginate();
        return TaskResource::collection($tasks);
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $task = Task::create($request->validated());
        return new TaskResource($task);
    }

    /**
     * Display the specified task.
     */
    public function show(Task $task)
    {
        $task->load('user');
        return new TaskResource($task);
    }

    /**
     * Update the specified task in storage.
     */
    public function update(StoreTaskRequest $request, Task $task)
    {
        $task->update($request->validated());
        return new TaskResource($task);
    }

    /**
     * Remove the specified task from storage.
     * @param  \App\Models\Task  $task  Instance of the task to be deleted.
     * @return \Illuminate\Http\Response
     */
    
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->noContent();
    }
}
