<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="Usuario",
 *     description="Modelo de usuario",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Juan Pérez"),
 *     @OA\Property(property="email", type="string", example="juan@example.com"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-30T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-30T12:30:00Z"),
 * )
 */

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/users",
     *     summary="Obtener todos los usuarios",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuarios obtenida con éxito",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/User"))
     *     ),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function index()
    {
        return response()->json(User::all(), 200);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/users/{id}",
     *     summary="Obtener un usuario por ID",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Usuario obtenido con éxito", @OA\JsonContent(ref="#/components/schemas/User")),
     *     @OA\Response(response=401, description="No autorizado"),
     *     @OA\Response(response=404, description="Usuario no encontrado")
     * )
     */
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'Usuario no encontrado'], 404);
        return response()->json($user, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/users/{id}",
     *     summary="Actualizar un usuario",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Nuevo Nombre"),
     *             @OA\Property(property="email", type="string", example="nuevo@email.com")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Usuario actualizado con éxito"),
     *     @OA\Response(response=401, description="No autorizado"),
     *     @OA\Response(response=404, description="Usuario no encontrado")
     * )
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'Usuario no encontrado'], 404);

        $user->update($request->only(['name', 'email']));
        return response()->json(['message' => 'Usuario actualizado con éxito'], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/users/{id}",
     *     summary="Eliminar un usuario",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Usuario eliminado con éxito"),
     *     @OA\Response(response=401, description="No autorizado"),
     *     @OA\Response(response=404, description="Usuario no encontrado")
     * )
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'Usuario no encontrado'], 404);

        $user->delete();
        return response()->json(['message' => 'Usuario eliminado con éxito'], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/user-statistics",
     *     summary="Obtener estadísticas de usuarios registrados",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Estadísticas de usuarios obtenidas con éxito",
     *         @OA\JsonContent(
     *             @OA\Property(property="usuarios_hoy", type="integer", example=5),
     *             @OA\Property(property="usuarios_esta_semana", type="integer", example=30),
     *             @OA\Property(property="usuarios_este_mes", type="integer", example=100)
     *         )
     *     ),
     *     @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function userStatistics()
    {
        $daily = User::whereDate('created_at', Carbon::today())->count();
        $weekly = User::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $monthly = User::whereMonth('created_at', Carbon::now()->month)->count();

        return response()->json([
            'usuarios_hoy' => $daily,
            'usuarios_esta_semana' => $weekly,
            'usuarios_este_mes' => $monthly,
        ], 200);
    }
}
