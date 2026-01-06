<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:api', 'role:admin']);
    }

    /**
     * Listar permisos
     * @return JsonResponse
     */
    public function index()
    {
        $permissions = Permission::all();
        return response()->json(['data' => $permissions]);
    }

    /**
     * Crear un nuevo permiso.
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:permissions,name']);
        $permission = Permission::create(['name' => $request->name]);
        return response()->json(['data' => $permission], 201);
    }

    /**
     * Mostrar un permiso.
     * @return JsonResponse
     */
    public function show(Permission $permission)
    {
        return response()->json(['data' => $permission]);
    }

    /**
     * Actualizar un permiso específico.
     * @return JsonResponse
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate(
            [
                'name' => 'required|string|unique:permissions,name,' . $permission->id
            ],
            [
                'name.required' => 'El campo name es requerido.',
                'name.unique' => 'El nombre del permiso ya existe.',
            ]
        );
        $permission->update(['name' => $request->name]);
        return response()->json(['data' => $permission], 201);
    }

    /**
     * Eliminar un permiso específico.
     * @return JsonResponse
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return response()->noContent();
    }
}
