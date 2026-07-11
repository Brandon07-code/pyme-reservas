<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        // auth('api') fuerza a Laravel a usar JWT en lugar de Cookies/Sesiones web
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Credenciales incorrectas o usuario no autorizado'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(auth('api')->user());
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60,
            'role_id'      => auth('api')->user()->role_id // Retornamos el rol para el frontend
        ]);
    }
}