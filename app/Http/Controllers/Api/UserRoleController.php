<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    // Sólo pueden acceder a estas rutas y realizar estas acciones los usuarios autenticados y con rol admin.
    public function __construct()
    {
        $this->middleware(['auth:api', 'role:admin']);
    }

    /** 
     * Listar los roles de un usuario específico.
     * 
     * @param User $user
     * @return JsonResponse
     * GET /users/{user}/roles
     */
    public function index(User $user)
    {
        return response()->json(['data' => $user->getRoleNames()]);
    }

    /** 
     * Asignar un rol a un usuario específico.
     * 
     * @param User $user
     * @param Role $role
     * @return JsonResponse
     * POST /users/{user}/roles/{role}
     */
    public function assign(User $user, Role $role)
    {
        if ($user->hasRole($role)) {
            response()->json(['message' => 'El usuario ya tiene asignado este rol.'], 409);
        }
        $user->assignRole($role);
        return response()->json(['message' => 'Rol asignado correctamente al usuario.', 'data' => $user->getRoleNames()], 201);
    }

    /** 
     * Modificar los roles de un usuario específico.
     * 
     * @param User $user
     * @param Request $role {"roles":["auditor","user"...]}
     * @return JsonResponse
     * POST /users/{user}/roles/bulk
     */
    public function assignBulk(User $user, Request $request)
    {
        $request->validate(
            [
                'roles' => 'required|array',
                'roles.*' => 'required|string|exists:roles,name'
            ],
            [
                'roles.array' => 'El campo roles debe ser de tipo array.',
                'roles.*.required' => 'Debe incluir al menos un elemento.',
                'roles.*.string' => 'Cada rol debe ser un string válido.',
                'roles.*.exists' => 'El rol no existe.'
            ]
        );
        // Reemplaza todos los roles del usuario
        $user->syncRoles($request->roles);

        return response()->json(['message' => 'Roles asignados correctamente al usuario.', 'data' => $user->getRoleNames()], 201);
    }

    /**  
     * Remover un rol a un usuario específico.
     * 
     * @param User $user
     * @param Role $role
     * @return JsonResponse
     * DELETE /users/{user}/roles/{role}
     */
    public function remove(User $user, Role $role)
    {
        if (!$user->hasRole($role)) {
            response()->json(['message' => 'El usuario no tiene asignado este rol.'], 409);
        }
        $user->removeRole($role);
        return response()->json(['message' => 'Se ha removido el rol correctamente.', 'data' => $user->getRoleNames()], 201);
    }
}
