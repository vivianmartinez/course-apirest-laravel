<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Routing\Controllers\Middleware;

class TaskController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    /**
     * Lista todas las tareas disponibles.
     *
     * Este método obtiene todas las tareas y las transforma mediante TaskFullResource para devolver una colección.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $tasks = Task::with(['author', 'assigned', 'category', 'comments.user'])->getOrPaginate();
        return TaskResource::collection($tasks);
    }

    /**
     * Crea una nueva tarea y devuelve su recurso.
     * Payload esperado (JSON):
     * - title: string
     * - description: string
     * - status: string
     * - due_date: string (YYYY-MM-DD)
     * - category_id: integer
     * @param  \App\Http\Requests\StoreTaskRequest  $request
     * @return \App\Http\Resources\TaskResource
     */
    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();
        // Usuario autenticado JWT 
        $validated['created_by'] = auth('api')->id();
        $task = Task::create($validated);
        return new TaskResource($task->load(['author', 'assigned', 'category']));
    }

    /**
     * Muestra una tarea específica.
     *
     * @param  \App\Models\Task  $task
     * @return \App\Http\Resources\TaskResource
     */
    public function show(Task $task)
    {
        $task->load(['author', 'assigned', 'category', 'comments.user']);
        return new TaskResource($task);
    }

    /**
     * Actualiza una tarea y devuelve su recurso actualizado.
     *
     * Payload esperado (JSON):
     * - title: string
     * - description: string
     * - status: string
     * - due_date: string (YYYY-MM-DD)
     * - category_id: integer
     * 
     * @param  \App\Http\Requests\UpdateTaskRequest  $request
     * @param  \App\Models\Task  $task  Instancia de Task.
     * @return \App\Http\Resources\TaskResource
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->validated());
        return new TaskResource($task->load(['author', 'assigned', 'category', 'comments']));
    }

    /**
     * Elimina una tarea específica.
     * 
     * @param  \App\Models\Task  $task  Instancia de Task.
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->noContent();
    }
}
