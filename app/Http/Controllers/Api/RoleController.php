<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;


class RoleController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:api', 'role:admin']);
    }

    /**
     * Listar los roles.
     * @return JsonResponse
     */
    public function index()
    {
        $roles = Role::all();
        return response()->json(['data' => $roles], 201);
    }

    /**
     * Crear un nuevo rol.
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:roles,name']);
        $role = Role::create(['name' => $request->name]);
        return response()->json(['data' => $role], 201);
    }

    /**
     * Mostrar un rol específico.
     * @return JsonResponse
     */
    public function show(Role $role)
    {
        return response()->json(['data' => $role]);
    }

    /**
     * Actualizar un rol.
     * @return JsonResponse
     */
    public function update(Request $request, Role $role)
    {
        $request->validate(['name' => 'required|string|unique:roles,name,' . $role->id]);
        $role->update(['name' => $request->name]);
        return response()->json(['data' => $role]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return response()->noContent();
    }
}
