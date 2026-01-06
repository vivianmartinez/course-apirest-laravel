<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\RolePermissionController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserRoleController;
use Illuminate\Support\Facades\Route;

// Autenticación
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login',    [AuthController::class, 'login']);
Route::post('auth/logout',   [AuthController::class, 'logout']);
Route::post('auth/refresh',  [AuthController::class, 'refresh']);
Route::get('auth/me',        [AuthController::class, 'me']);

// Rutas -> users, tasks, categories
Route::apiResources([
    'users' => UserController::class,
    'tasks' => TaskController::class,
    'categories' => CategoryController::class,
]);
// comments
Route::apiResource('comments', CommentController::class)->except(['store', 'index']);
Route::get('tasks/{task}/comments', [CommentController::class, 'byTask']);
Route::post('tasks/{task}/comments/bulk', [CommentController::class, 'storeBulkByTask']);

// Roles y permisos
Route::apiResources([
    'roles' => RoleController::class,
    'permissions' => PermissionController::class
]);

// Acciones para administrar roles de usuarios
Route::prefix('users/{user}')->group(function () {
    Route::get('roles', [UserRoleController::class, 'index']);
    Route::post('roles/bulk', [UserRoleController::class, 'assignBulk']);
    Route::post('roles/{role}', [UserRoleController::class, 'assign']);
    Route::delete('roles/{role}', [UserRoleController::class, 'remove']);
});

// Acciones para administrar permisos de usuarios
Route::prefix('roles/{role}')->group(function () {
    Route::get('permissions', [RolePermissionController::class, 'index']);
    Route::post('permissions/{permission}', [RolePermissionController::class, 'attach']);
    Route::delete('permissions/{permission}', [RolePermissionController::class, 'detach']);
});
