<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *      title="API de Gestión de Usuarios",
 *      version="1.0",
 *      description="Documentación de la API de Gestión de Usuarios con Swagger en Laravel 11"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     description="Ingresa el toquen generado al iniciar sesión",
 *     name="Authorization",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 * )
 */

abstract class Controller
{
    //
}
