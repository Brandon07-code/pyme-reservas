<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

  public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'primer_nombre' => ['required', 'string', 'max:100'],
            'primer_apellido' => ['required', 'string', 'max:100'],
            'telefono' => ['required', 'string', 'regex:/^3[\d]{9}$/'], 
            'email' => ['required', 'string', 'lowercase', 'email', 'max:150', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'telefono.regex' => 'El teléfono debe ser un celular colombiano válido (Ej: 3001234567).'
        ]);

        $user = User::create([
            'role_id' => 3,
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

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('portal.index');
    }
}