<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBulkCommentsRequest;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store newly comments for the task.
     */
    public function storeByTask(StoreCommentRequest $request, Task $task)
    {
        $validated = $request->validated();
        $validated['user_id'] = 1;

        $comment = $task->comments()->create($validated);
        return new CommentResource($comment->load('task'));
    }

    public function storeBulkByTask(StoreBulkCommentsRequest $request, Task $task)
    {
        $validated = $request->validated();
        $user = 1; // temporal hasta implementar JWT

        $comments = collect($validated['comments'])->map(function($comment) use($user,$task){
            return [
                'content' => $comment['content'],
                'user_id' => $user,
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
     * Display the specified comment.
     */
    public function show(Comment $comment)
    {
        $comment->load(['user','task']); 
        return new CommentResource($comment);
    }

    /**
     * Update the specified comment in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $comment->update($request->validated());
        return new CommentResource($comment->load(['user','task']));
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->noContent();
    }

    public function byTask(Task $task){
        $comments = $task->comments()->with('user')->get();
        return CommentResource::collection($comments);
    }
}
