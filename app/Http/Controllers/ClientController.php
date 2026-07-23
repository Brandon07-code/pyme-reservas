<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $nuevosMes = $request->boolean('nuevos_mes');

        $query = Client::search($search)->latest();

        if ($nuevosMes) {
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        }

        $clients = $query->paginate(10);

        $totalClients = Client::count();
        $activeClients = Client::where('estado', 1)->count();
        $inactiveClients = Client::where('estado', 0)->count();

        return view('clients.index', compact('clients', 'search', 'nuevosMes', 'totalClients', 'activeClients', 'inactiveClients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'primer_nombre' => 'required|string|max:100',
            'segundo_nombre' => 'nullable|string|max:100',
            'primer_apellido' => 'required|string|max:100',
            'segundo_apellido' => 'nullable|string|max:100',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email|unique:clients,email',
            'password' => 'required|string|min:8',
            'estado' => 'boolean'
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated) {
            // 1. Crear el Usuario con rol de Cliente (role_id = 3)
            $user = \App\Models\User::create([
                'primer_nombre' => $validated['primer_nombre'],
                'primer_apellido' => $validated['primer_apellido'],
                'email' => $validated['email'],
                'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
                'role_id' => 3, // 3 = Cliente
                'estado' => true
            ]);

            // 2. Crear el perfil de Cliente vinculado al Usuario
            Client::create([
                'user_id' => $user->id,
                'primer_nombre' => $validated['primer_nombre'],
                'segundo_nombre' => $validated['segundo_nombre'] ?? null,
                'primer_apellido' => $validated['primer_apellido'],
                'segundo_apellido' => $validated['segundo_apellido'] ?? null,
                'telefono' => $validated['telefono'],
                'email' => $validated['email'],
                'estado' => $validated['estado'] ?? 1,
            ]);
        });

        return redirect()->route('clientes.index')->with('success', 'Cliente y usuario web registrados correctamente.');
    }

    public function edit(Client $cliente)
    {
        return view('clients.edit', compact('cliente'));
    }

    public function update(Request $request, Client $cliente)
    {
        $validated = $request->validate([
            'primer_nombre' => 'required|string|max:100',
            'segundo_nombre' => 'nullable|string|max:100',
            'primer_apellido' => 'required|string|max:100',
            'segundo_apellido' => 'nullable|string|max:100',
            'telefono' => 'required|string|max:20',
            'email' => 'nullable|email|unique:clients,email,' . $cliente->id,
            'estado' => 'boolean'
        ]);

        $cliente->update($validated);
        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Client $cliente)
    {
        // Toggle inteligente de estado
        $nuevoEstado = !$cliente->estado;
        $cliente->update(['estado' => $nuevoEstado]);

        $mensaje = $nuevoEstado ? 'Cliente activado correctamente.' : 'Cliente desactivado correctamente.';
        return redirect()->route('clientes.index')->with('success', $mensaje);
    }
}