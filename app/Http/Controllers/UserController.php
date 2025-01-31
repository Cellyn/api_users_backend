<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // Obtener todos los usuarios
    public function index()
    {
        return response()->json(User::all(), 200);
    }

    // Obtener usuario por id
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'Usuario no encontrado'], 404);
        return response()->json($user, 200);
    }

    // Actualizar usuario
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'Usuario no encontrado'], 404);

        $user->update($request->only(['name', 'email']));
        return response()->json(['message' => 'Usuario actualizado con éxito'], 200);
    }

    // Eliminar usuario
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'Usuario no encontrado'], 404);

        $user->delete();
        return response()->json(['message' => 'Usuario eliminado con éxito'], 200);
    }

    // Mostrar estadísticas
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
