<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    // Sólo pueden acceder a estas rutas y realizar estas acciones los usuarios con rol admin
    public function __construct() {
        $this->middleware(['auth:api','role:admin']);
    }

    // GET /users/{user}/roles
    public function listRoles(User $user)
    {
        return response()->json(['data' => $user->getRoleNames()]);
    }

    // POST /users/{user}/roles
    public function assignRole(User $user, Request $request)
    {
        $request->validate(['role' => 'required|string|exists:roles,name']);
        $user->assignRole($request->role);
        return response()->json(['message' => 'Rol asignado', 'data' => $user->getRoleNames()], 201);
    }

    // DELETE /users/{user}/roles
    public function removeRole(User $user, Request $request) 
    {
        $request->validate([ 'role' => 'required|string|exists:roles,name' ]); 
        $user->removeRole($request->role);
        return response()->json(['message' => 'Rol eliminado', 'data' => $user->getRoleNames()], 201);
    }
}
