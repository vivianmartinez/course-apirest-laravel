<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserFullResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    /**
     * Declara los middlewares asociados a este controlador.
     *
     * Laravel ejecuta este método estático al registrar las rutas del controlador.
     * Permite aplicar middlewares de forma centralizada, sin configurarlos en el archivo de rutas.
     * En este caso, se protege todo el controlador con el middleware 'auth:api',
     * exigiendo un token JWT válido para acceder a cualquiera de sus acciones.
     * En este controlador todos sus métodos se encuentran protegidos excepto login y register.
     */
    // public static function middleware(): array
    // {
    //     return [
    //         new Middleware('auth:api', except: ['login', 'register']),
    //     ];
    // }

    public function __construct()
    {
        $this->middleware(['auth:api'])->except(['login','register']);
    }

    /**
     * Registra un nuevo usuario en el sistema y genera un token JWT.
     *
     * Este método valida los datos enviados en la petición, crea un nuevo usuario
     * en la base de datos y marca su email como verificado. Tras el registro,
     * se genera un token JWT asociado al usuario recién creado para permitirle
     * autenticarse inmediatamente sin necesidad de iniciar sesión.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(StoreUserRequest $request)
    {
        $validated = $request->validated();
        $validated['email_verified_at'] = now();
        $validated['password'] = Hash::make($validated['password']);
        //Crear usuario
        $user = User::create($validated);
        // Generar token
        $token = JWTAuth::fromUser($user);
        // Rol por defecto 
        $user->assignRole('user');

        return response()->json([
            'user'  => $user,
            'roles' => $user->getRoleNames(),
            'token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => JWTAuth::factory()->getTTL() * 60,
        ], 201);
    }

    /**
     * Autentica al usuario mediante sus credenciales y genera un token JWT.
     *
     * Este método valida el email y la contraseña enviados en la petición.
     * Si las credenciales son correctas, se genera un token JWT asociado al usuario
     * y se devuelve en la respuesta. Si son incorrectas, se retorna un error 401.
     *
     * El token devuelto debe enviarse en el header Authorization como:
     *     Bearer <token>
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        /** @var \PHPOpenSourceSaver\JWTAuth\JWTGuard $auth */
        $auth = auth('api');

        if (! $token = $auth->attempt($credentials)) {
            return response()->json([
                'message' => 'Credenciales inválidas',
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Devuelve la información del usuario autenticado.
     *
     * Este método obtiene al usuario asociado al token JWT enviado en la petición.
     * Es útil para mostrar datos del perfil, validar sesiones activas o cargar
     * información del usuario en el cliente sin necesidad de realizar consultas
     * adicionales.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = auth('api')->user();
        return response()->json(
            [
              'user' => $user, 
            //   'roles' => $user->getRoleNames(), 
            //   'permissions' => $user->getAllPermissions()->pluck('name'),  
            ]
        );
    }

    /**
     * Cierra la sesión del usuario autenticado invalidando su token JWT.
     *
     * Este método invalida el token actual, impidiendo que pueda volver a usarse.
     * Es útil para cerrar sesión de forma explícita desde el cliente. Una vez
     * invalidado el token, cualquier petición posterior requerirá un nuevo inicio
     * de sesión para obtener un token válido.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        /** @var \PHPOpenSourceSaver\JWTAuth\JWTGuard $auth */
        $auth = auth('api');
        $auth->logout();
        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

    /**
     * Refresca el token JWT del usuario autenticado.
     *
     * Este método invalida el token actual y genera uno nuevo, manteniendo la sesión
     * del usuario activa sin necesidad de volver a iniciar sesión. El token se obtiene
     * automáticamente desde el header Authorization de la petición.
     *
     * Se utiliza cuando el token está próximo a expirar y el cliente necesita renovarlo.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        /** @var \PHPOpenSourceSaver\JWTAuth\JWTGuard $auth */
        $auth = auth('api');
        return $this->respondWithToken($auth->refresh());
    }

    /**
     * Devuelve el token JWT.
     *
     * Este método centraliza el formato de respuesta utilizado al generar o
     * refrescar un token. Incluye el token, su tipo y el tiempo de expiración
     * en segundos.
     *
     * @param  string  $token  El token JWT generado.
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => JWTAuth::factory()->getTTL() * 60,
        ]);
    }
}
