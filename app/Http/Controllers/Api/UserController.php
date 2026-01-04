<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserFullResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        return new UserCollection(User::all());
    }

    /**
     * Store a newly user in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated(); 
        $validated['password'] = bcrypt($validated['password']); 
        $user = User::create($validated); 
        return new UserResource($user);
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['tasks.category','tasks.comments']);
        return new UserFullResource($user);
    }


    /**
     * Update the specified user in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();
        if(isset($validated['password'])) $validated['password'] = bcrypt($validated['password']);
        $user->update($validated);
        return new UserFullResource($user->load(['tasks.category','tasks.comments']));
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->noContent();
    }
}
