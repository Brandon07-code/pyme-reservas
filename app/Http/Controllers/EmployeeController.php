<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
  public function index(Request $request)
    {
        $search = $request->get('search');
        $employees = Employee::with('user')->search($search)->latest()->paginate(10);

        $total = Employee::count();
        $activos = Employee::where('estado', 1)->count();
        $inactivos = Employee::where('estado', 0)->count();

        return view('employees.index', compact('employees', 'search', 'total', 'activos', 'inactivos'));
    }

    public function create()
    {
        // Solo usuarios que no tienen un empleado asociado y están activos
        $users = User::whereDoesntHave('employee')->where('estado', true)->get();
        return view('employees.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'primer_nombre' => 'required|string|max:100',
            'primer_apellido' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role_id' => 'required|in:1,2',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'especialidad' => 'nullable|string|max:100',
            'estado' => 'boolean'
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated) {
            // 1. Crear el Usuario
            $user = \App\Models\User::create([
                'primer_nombre' => $validated['primer_nombre'],
                'primer_apellido' => $validated['primer_apellido'],
                'email' => $validated['email'],
                'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
                'role_id' => $validated['role_id'],
                'estado' => true
            ]);

            // 2. Crear el perfil de Empleado vinculado al Usuario
            Employee::create([
                'user_id' => $user->id,
                'telefono' => $validated['telefono'] ?? null,
                'direccion' => $validated['direccion'] ?? null,
                'especialidad' => $validated['especialidad'] ?? null,
                'estado' => $validated['estado'] ?? 1,
            ]);
        });

        return redirect()->route('empleados.index')->with('success', 'Empleado y usuario creados correctamente.');
    }

    public function edit(Employee $empleado)
    {
        return view('employees.edit', compact('empleado'));
    }

    public function update(Request $request, Employee $empleado)
    {
        $validated = $request->validate([
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'especialidad' => 'nullable|string|max:100',
            'estado' => 'boolean'
        ]);

        $empleado->update($validated);
        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado correctamente.');
    }




    public function destroy(Employee $empleado)
    {
        $nuevoEstado = !$empleado->estado;
        $empleado->update(['estado' => $nuevoEstado]);
        $mensaje = $nuevoEstado ? 'Empleado activado correctamente.' : 'Empleado desactivado correctamente.';
        return redirect()->route('empleados.index')->with('success', $mensaje);
    }
}