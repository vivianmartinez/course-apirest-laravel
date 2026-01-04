<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResources([
    'users' => UserController::class,
    'tasks' => TaskController::class,
    'categories' => CategoryController::class,
]);
// comments
Route::apiResource('comments',CommentController::class)->except(['store','index']);
Route::get('tasks/{task}/comments', [CommentController::class, 'byTask']);
Route::post('tasks/{task}/comments/bulk', [CommentController::class, 'storeBulkByTask']);

