<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::latest()->paginate(10);
        return view('clients.index', compact('clients'));
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
            'email' => 'nullable|email|unique:clients,email',
            'estado' => 'boolean'
        ]);

        Client::create($validated);
        return redirect()->route('clientes.index')->with('success', 'Cliente registrado correctamente.');
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
        $cliente->update(['estado' => false]); // Eliminación lógica (desactivar)
        return redirect()->route('clientes.index')->with('success', 'Cliente desactivado correctamente.');
    }
}