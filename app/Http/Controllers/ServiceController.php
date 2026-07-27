<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $estadoFilter = $request->get('estado');
        
        $query = Service::with('category')->search($search)->latest();
        
        if ($estadoFilter !== null && $estadoFilter !== '') {
            $query->where('estado', (int) $estadoFilter);
        }
        
        $services = $query->paginate(12);

        $total = Service::count();
        $activos = Service::where('estado', 1)->count();
        $inactivos = Service::where('estado', 0)->count();

        return view('services.index', compact('services', 'search', 'total', 'activos', 'inactivos', 'estadoFilter'));
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
            'imagen_url' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'estado' => 'boolean'
        ]);

        if ($request->hasFile('imagen_url')) {
            $file = $request->file('imagen_url');
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('services', $fileName, 'public');
            $validated['imagen_url'] = 'storage/' . $path;
        }

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
            'imagen_url' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'estado' => 'boolean'
        ]);

        if ($request->hasFile('imagen_url')) {
            if ($servicio->imagen_url && Storage::disk('public')->exists(str_replace('storage/', '', $servicio->imagen_url))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $servicio->imagen_url));
            }
            $file = $request->file('imagen_url');
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('services', $fileName, 'public');
            $validated['imagen_url'] = 'storage/' . $path;
        }

        $servicio->update($validated);
        return redirect()->route('servicios.index')->with('success', 'Servicio actualizado correctamente.');
    }

    public function destroy(Service $servicio)
    {
        // Si el servicio tiene reservas históricas, solo se puede desactivar/activar
        if ($servicio->reservations()->exists()) {
            $nuevoEstado = !$servicio->estado;
            $servicio->update(['estado' => $nuevoEstado]);
            $mensaje = $nuevoEstado ? 'Servicio activado correctamente.' : 'Servicio desactivado correctamente.';
            return redirect()->route('servicios.index')->with('success', $mensaje);
        }

        // Si no tiene historial, se elimina definitivamente
        if ($servicio->imagen_url && Storage::disk('public')->exists(str_replace('storage/', '', $servicio->imagen_url))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $servicio->imagen_url));
        }
        $servicio->delete();
        return redirect()->route('servicios.index')->with('success', 'Servicio eliminado definitivamente.');
    }

    public function exportPdf(Request $request)
    {
        ini_set('max_execution_time', 120);
        $search = $request->get('search');
        $estadoFilter = $request->get('estado');

        $query = Service::with('category')->search($search)->latest();
        if ($estadoFilter !== null && $estadoFilter !== '') {
            $query->where('estado', (int) $estadoFilter);
        }
        $servicios = $query->get();

        $pdf = Pdf::loadView('pdf.servicios', compact('servicios'))->setPaper('a4', 'landscape');
        return $pdf->download('reporte-servicios-jym-' . date('Y-m-d') . '.pdf');
    }
}