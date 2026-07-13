<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'primer_nombre' => 'required|string|max:100',
            'primer_apellido' => 'required|string|max:100',
            'telefono' => ['required', 'string', 'regex:/^3[\d]{9}$/'],
            'email' => 'required|string|email|max:150|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'role_id' => 3, // Cliente
            'primer_nombre' => $request->primer_nombre,
            'primer_apellido' => $request->primer_apellido,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'estado' => 1,
        ]);

        Client::create([
            'user_id' => $user->id,
            'primer_nombre' => $request->primer_nombre,
            'primer_apellido' => $request->primer_apellido,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'estado' => 1,
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }

        return response()->json(compact('token'));
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Sesión cerrada exitosamente']);
    }
}