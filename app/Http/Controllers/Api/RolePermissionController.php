<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    // Sólo pueden acceder a estas acciones los usuarios autenticados y con el rol admin.
    public function __construct()
    {
        $this->middleware(['auth:api', 'role:admin']);
    }

    /**
     * Listar permisos de un rol.
     * @param Role $role
     * @return JsonResponse
     */
    public function index(Role $role)
    {
        return response()->json(['data' => $role->getPermissionNames()]);
    }

    /** 
     * Asignar un permiso a un rol específico.
     * 
     * @param Role $role
     * @param Permission $permission
     * @return JsonResponse
     * POST /roles/{role}/permissions/{permission}
     */
    public function attach(Role $role, Permission $permission)
    {
        if ($role->permissions()->where('permission_id', $permission->id)->exists()) {
            return response()->json(['message' => 'El permiso ya se encuentra asignado a este rol'], 409);
        }
        $role->permissions()->attach($permission->id);
        return response()->json(['message' => 'El permiso ha sido asignado al rol correctamente.', 'data' => $role->permissions], 201);
    }

    /** 
     * Remover un permiso a un usuario específico.
     * 
     * @param Role $role
     * @param Permission $permission
     * @return JsonResponse
     * DELETE /roles/{role}/permissions/{permission}
     */
    public function detach(Role $role, Permission $permission)
    {
        if (!$role->permissions()->where('permission_id', $permission->id)->exists()) {
            return response()->json(['message' => 'El permiso no se encuentra asignado a este rol'], 409);
        }
        $role->permissions()->detach($permission->id);
        return response()->json(['message' => 'El permiso ha sido eliminado del rol correctamente.', 'data' => $role->permissions], 201);
    }
}
