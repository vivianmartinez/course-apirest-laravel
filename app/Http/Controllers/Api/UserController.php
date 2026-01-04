<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserFullResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [new Middleware('auth:api')];
    }

    /**
     * Lista todos los usuarios.
     *
     * Este método obtiene todas las tareas y las transforma mediante UserCollection para devolver una colección.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return new UserCollection(User::all());
    }

    /**
     * Crea un usuario nuevo y devuelve su recurso.
     *
     * Payload esperado (JSON):
     * - name: string
     * - email: email
     * - password: string|min 8 characters
     * 
     * @param  \App\Http\Requests\StoreUserRequest  $request
     * @param  \App\Models\User  $user
     * @return \App\Http\Resources\UserResource
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);
        return new UserResource($user); // sin tareas
    }

    /**
     * Muestra un usuario específico.
     *
     * @param  \App\Models\User  $user
     * @return \App\Http\Resources\UserFullResource
     */
    public function show(User $user)
    {
        $user->load(['tasks.category', 'tasks.comments']);
        return new UserFullResource($user);
    }


    /**
     * Actualiza un usuario y devuelve su recurso actualizado.
     *
     * Payload esperado (JSON):
     * - name: string
     * - email: email
     * - password: string|min 8 characters
     * 
     * @param  \App\Http\Requests\UpdateUserRequest  $request
     * @param  \App\Models\User  $user
     * @return \App\Http\Resources\UserFullResource
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();
        if (isset($validated['password'])) $validated['password'] = Hash::make($validated['password']);
        $user->update($validated);
        return new UserFullResource($user->load(['tasks.category', 'tasks.comments']));
    }

    /**
     * Elimina un usuario específico.
     * 
     * @param  \App\Models\User  $user  Instancia de User.
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->noContent();
    }
}
