<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Product;
use App\Models\ServiceCategory;
use App\Models\ProductCategory;

class PortalController extends Controller
{
    public function index()
    {
        // Traemos todos los servicios activos agrupados por su categoría para mostrarlo ordenado
        $categoriasServicios = ServiceCategory::with(['services' => function($query) {
            $query->where('estado', 1);
        }])->where('estado', 1)->get();

        // Traemos los productos activos, paginados por si el catálogo de perfumes crece
        $productos = Product::where('estado', 1)->latest()->get();

        return view('portal.index', compact('categoriasServicios', 'productos'));
    }
}