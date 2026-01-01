<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        return TaskResource::collection(Task::with('user')->get());
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified task.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
