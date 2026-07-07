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
            'user_id' => 'required|exists:users,id|unique:employees,user_id',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'especialidad' => 'nullable|string|max:100',
            'estado' => 'boolean'
        ]);

        Employee::create($validated);
        return redirect()->route('empleados.index')->with('success', 'Empleado creado correctamente.');
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