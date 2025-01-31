<?php

namespace App\Http\Middleware;

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;

class RefreshTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::user();

        if ($user) {
            // Obtener el token actual desde la base de datos
            $token = $user->tokens()->latest()->first();

            if ($token) {
                $expiresAt = Carbon::parse($token->created_at)->addMinutes(config('sanctum.expiration'));

                // Si faltan menos de 1 minuto para expirar, renovamos el token
                if (now()->greaterThanOrEqualTo($expiresAt->subMinute())) {
                    $token->delete(); // Borrar el token actual

                    // Crear un nuevo token
                    $newToken = $user->createToken('auth_token')->plainTextToken;

                    // Agregar el nuevo token a la respuesta
                    $request->headers->set('New-Token', $newToken);
                }
            }
        }
        return $next($request);
    }
}
