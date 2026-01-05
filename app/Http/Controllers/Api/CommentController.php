<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBulkCommentsRequest;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Task;


class CommentController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    /**
     * Crea nuevo comentario para una tarea.
     * 
     * Payload esperado (JSON):
     * - content: string
     * 
     * @param  \App\Http\Requests\StoreCommentRequest  $request
     * @param  \App\Models\Task  $task  Instancia de Task.
     * @return \App\Http\Resources\CommentResource
     */
    public function storeByTask(StoreCommentRequest $request, Task $task)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth('api')->id();

        $comment = $task->comments()->create($validated);
        return new CommentResource($comment->load('task'));
    }

    /**
     * Crea múltiples comentarios en una sola petición para una tarea específica.
     *
     * Payload esperado (JSON):
     * - comments: array de objetos
     *      - content: string
     * 
     * @param  \App\Http\Requests\StoreCommentRequest  $request
     * @param  \App\Models\Task  $task  Instancia de Task.
     * @return \App\Http\Resources\CommentResource
     */
    public function storeBulkByTask(StoreBulkCommentsRequest $request, Task $task)
    {
        $validated = $request->validated();
        $created_by =  auth('api')->id();

        $comments = collect($validated['comments'])->map(function ($comment) use ($created_by,$task) {
            return [
                'content' => $comment['content'],
                'user_id' => $created_by,
                'task_id' => $task->id,
                'created_at' => now(),
                'updated_at' => now()
            ];
        })->toArray();
        // Bulk de comentarios en una sola consulta
        $task->comments()->insert($comments);
        $newComments = $task->comments()
            ->latest()
            ->take(count($comments))
            ->get()->load('user');
        return CommentResource::collection($newComments);
    }

    /**
     * Muestra un comentario específico.
     *
     * @param  \App\Models\Comment  $comment
     * @return \App\Http\Resources\CommentResource
     */
    public function show(Comment $comment)
    {
        $comment->load(['user', 'task']);
        return new CommentResource($comment);
    }

    /**
     * Actualiza un comentario y devuelve su recurso actualizado.
     *
     * Payload esperado (JSON):
     * - content: string
     * 
     * @param  \App\Http\Requests\UpdateCommentRequest  $request
     * @return \App\Http\Resources\CommentResource
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $comment->update($request->validated());
        return new CommentResource($comment->load(['user', 'task']));
    }

    /**
     * Elimina un comentario específico.
     * 
     * @param  \App\Models\Comment  $comment  Instancia de Comment.
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->noContent();
    }

    /**
     * Lista los comentarios de una tarea específica.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function byTask(Task $task)
    {
        $comments = $task->comments()->with('user')->get();
        return CommentResource::collection($comments);
    }
}
