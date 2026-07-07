<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $services = Service::with('category')->search($search)->latest()->paginate(10);

        $total = Service::count();
        $activos = Service::where('estado', 1)->count();
        $inactivos = Service::where('estado', 0)->count();

        return view('services.index', compact('services', 'search', 'total', 'activos', 'inactivos'));
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