@extends('layouts.app')

@section('title', 'Gestionar Horarios')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Turnos Laborales</h1>
                <p class="text-gray-500 mt-1">Empleado: <span class="font-bold text-indigo-600">{{ $empleado->user->primer_nombre }} {{ $empleado->user->primer_apellido }}</span></p>
            </div>
            <a href="{{ route('empleados.index') }}" class="text-gray-600 hover:text-gray-900 underline">Volver a empleados</a>
        </div>

        @if($errors->any()) 
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                <p class="font-bold">Error al guardar:</p>
                <ul class="list-disc ml-5">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div> 
        @endif

        <form action="{{ route('empleados.horarios.update', $empleado) }}" method="POST" class="bg-white shadow-md rounded-lg overflow-hidden">
            @csrf
            @method('PUT')

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Día</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">¿Labora?</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hora Entrada</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hora Salida</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($diasSemana as $num => $nombreDia)
                            @php
                                $horario = $horariosActuales->get($num);
                                $disponible = $horario ? $horario->disponible : false;
                                $hInicio = $horario ? \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') : '08:00';
                                $hFin = $horario ? \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') : '18:00';
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800">{{ $nombreDia }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <input type="checkbox" name="horarios[{{ $num }}][disponible]" value="1" {{ $disponible ? 'checked' : '' }} class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 cursor-pointer">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="time" name="horarios[{{ $num }}][hora_inicio]" value="{{ $hInicio }}" required class="border-gray-300 rounded-md shadow-sm border p-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="time" name="horarios[{{ $num }}][hora_fin]" value="{{ $hFin }}" required class="border-gray-300 rounded-md shadow-sm border p-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow transition">
                    Guardar Horarios
                </button>
            </div>
        </form>
    </div>
@endsection