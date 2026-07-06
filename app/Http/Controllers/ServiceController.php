<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with('category')->latest()->paginate(10);
        return view('services.index', compact('services'));
    }

    public function create()
    {
        $categories = ServiceCategory::where('estado', true)->get();
        return view('services.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_category_id' => 'required|exists:service_categories,id',
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string|max:255',
            'precio' => 'required|numeric|min:0',
            'duracion_minutos' => 'required|integer|min:1',
            'estado' => 'boolean'
        ]);

        Service::create($validated);
        return redirect()->route('servicios.index')->with('success', 'Servicio creado correctamente.');
    }

    public function edit(Service $servicio)
    {
        $categories = ServiceCategory::where('estado', true)->get();
        return view('services.edit', compact('servicio', 'categories'));
    }

    public function update(Request $request, Service $servicio)
    {
        $validated = $request->validate([
            'service_category_id' => 'required|exists:service_categories,id',
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string|max:255',
            'precio' => 'required|numeric|min:0',
            'duracion_minutos' => 'required|integer|min:1',
            'estado' => 'boolean'
        ]);

        $servicio->update($validated);
        return redirect()->route('servicios.index')->with('success', 'Servicio actualizado correctamente.');
    }

    public function destroy(Service $servicio)
    {
        $servicio->update(['estado' => false]); // Eliminación lógica
        return redirect()->route('servicios.index')->with('success', 'Servicio desactivado correctamente.');
    }
}