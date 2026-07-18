<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        // Eager Loading (with) y Scope (search)
        $users = User::with('role')->search($search)->latest()->paginate(10);

        // Tarjetas de Estadísticas
        $totalUsers = User::count();
        $activeUsers = User::where('estado', 1)->count();
        $inactiveUsers = User::where('estado', 0)->count();

        return view('users.index', compact('users', 'search', 'totalUsers', 'activeUsers', 'inactiveUsers'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'primer_nombre' => 'required|string|max:100',
            'segundo_nombre' => 'nullable|string|max:100',
            'primer_apellido' => 'required|string|max:100',
            'segundo_apellido' => 'nullable|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'estado' => 'boolean'
        ]);

        // Encriptar la contraseña
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);
        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $usuario)
    {
        $roles = Role::all();
        return view('users.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'primer_nombre' => 'required|string|max:100',
            'segundo_nombre' => 'nullable|string|max:100',
            'primer_apellido' => 'required|string|max:100',
            'segundo_apellido' => 'nullable|string|max:100',
            'email' => ['required', 'email', Rule::unique('users')->ignore($usuario->id)],
            'estado' => 'boolean',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Solo actualizar contraseña si el usuario escribió una nueva
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:6']);
            $validated['password'] = Hash::make($request->password);
        }
        
        if ($request->hasFile('avatar')) {
            if ($usuario->avatar && Storage::disk('public')->exists($usuario->avatar)) {
                Storage::disk('public')->delete($usuario->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $usuario->update($validated);
        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

 public function destroy(User $usuario)
    {
        // Invierte el estado actual (Si es 1 pasa a 0, si es 0 pasa a 1)
        $nuevoEstado = !$usuario->estado;
        
        $usuario->update(['estado' => $nuevoEstado]);

        // Mensaje dinámico
        $mensaje = $nuevoEstado ? 'Usuario activado correctamente.' : 'Usuario desactivado correctamente.';
        
        return redirect()->route('usuarios.index')->with('success', $mensaje);
    }
}