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

        $usuario = User::create($validated);
        $this->sincronizarPerfiles($usuario, $request);

        return redirect()->route('usuarios.index')->with('success', 'Usuario y perfiles creados correctamente.');
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
        $this->sincronizarPerfiles($usuario, $request);

        return redirect()->route('usuarios.index')->with('success', 'Usuario y perfiles actualizados correctamente.');
    }

    public function destroy(User $usuario)
    {
        // Proteger: no modificar la propia cuenta
        if ($usuario->id === auth()->id()) {
            return redirect()->route('usuarios.index')->with('error', 'No puedes desactivar o eliminar tu propia cuenta.');
        }

        // Proteger: garantizar que siempre exista al menos un administrador activo
        if ($usuario->role_id === 1 && $usuario->estado === 1) {
            $adminsActivos = User::where('role_id', 1)->where('estado', 1)->count();
            if ($adminsActivos <= 1) {
                return redirect()->route('usuarios.index')->with('error', 'No se puede desactivar al único administrador activo del sistema. Crea otro administrador primero.');
            }
        }

        // Verificar si tiene datos históricos vinculados
        $tieneHistorial = false;
        if ($usuario->client && ($usuario->client->reservations()->exists() || $usuario->client->orders()->exists())) {
            $tieneHistorial = true;
        }
        if ($usuario->employee && $usuario->employee->reservations()->exists()) {
            $tieneHistorial = true;
        }

        if ($tieneHistorial) {
            // Solo desactivar/activar si tiene historial
            $nuevoEstado = !$usuario->estado;
            $usuario->update(['estado' => $nuevoEstado]);
            $mensaje = $nuevoEstado ? 'Usuario activado correctamente.' : 'Usuario desactivado correctamente. No se puede eliminar porque tiene reservas o pedidos registrados.';
            return redirect()->route('usuarios.index')->with('success', $mensaje);
        }

        // Sin historial: eliminar registros hijos primero (respeta FK RESTRICT)
        if ($usuario->employee) {
            $usuario->employee->delete();
        }
        if ($usuario->client) {
            $usuario->client->delete();
        }
        $usuario->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado definitivamente.');
    }

    private function sincronizarPerfiles(User $usuario, Request $request)
    {
        $role_id = (int) $usuario->role_id;
        
        if (in_array($role_id, [1, 2])) {
            // Es Admin o Empleado -> Garantizar perfil en tabla empleados
            $telefono = $request->telefono ?? ($usuario->client ? $usuario->client->telefono : '0000000000');
            
            if (!$usuario->employee) {
                \App\Models\Employee::create([
                    'user_id' => $usuario->id,
                    'nombre' => $usuario->primer_nombre . ' ' . $usuario->primer_apellido,
                    'email' => $usuario->email,
                    'telefono' => $telefono,
                    'cargo' => $role_id == 1 ? 'Administrador' : 'Barbero',
                    'estado' => 1
                ]);
            } else {
                $usuario->employee->update([
                    'cargo' => $role_id == 1 ? 'Administrador' : 'Barbero',
                    'estado' => 1,
                    'telefono' => $telefono !== '0000000000' ? $telefono : $usuario->employee->telefono
                ]);
            }
            
            // Si tenía un perfil de cliente, lo desactivamos (no lo borramos para no perder historial)
            if ($usuario->client) {
                $usuario->client->update(['estado' => 0]);
            }
            
        } elseif ($role_id == 3) {
            // Es Cliente -> Garantizar perfil en tabla clientes
            $telefono = $request->telefono ?? ($usuario->employee ? $usuario->employee->telefono : '0000000000');
            
            if (!$usuario->client) {
                \App\Models\Client::create([
                    'user_id' => $usuario->id,
                    'primer_nombre' => $usuario->primer_nombre,
                    'segundo_nombre' => $usuario->segundo_nombre,
                    'primer_apellido' => $usuario->primer_apellido,
                    'segundo_apellido' => $usuario->segundo_apellido,
                    'email' => $usuario->email,
                    'telefono' => $telefono,
                    'estado' => 1
                ]);
            } else {
                $usuario->client->update([
                    'estado' => 1,
                    'telefono' => $telefono !== '0000000000' ? $telefono : $usuario->client->telefono
                ]);
            }
            // Si tenía un perfil de empleado, lo desactivamos (no lo borramos para no perder historial)
            if ($usuario->employee) {
                $usuario->employee->update(['estado' => 0]);
            }
        }
    }
}