@if($proximasCitas->isEmpty())
    <p class="text-gray-500 text-sm text-center py-8 italic">Tu silla está libre. No hay más citas para hoy.</p>
@else
    <ul class="divide-y divide-gray-100">
        @foreach($proximasCitas as $cita)
            @php
                $fechaFmt = \Carbon\Carbon::parse($cita->fecha)->format('Y-m-d');
                $fechaHoraFin = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $fechaFmt . ' ' . $cita->hora_fin);
                $puedeCompletarse = now()->greaterThanOrEqualTo($fechaHoraFin);
            @endphp

            <li class="py-4 flex justify-between items-center group">
                <div>
                    <p class="font-extrabold text-gray-900 text-sm">{{ \Carbon\Carbon::parse($cita->hora_inicio)->format('h:i A') }}</p>
                    <p class="text-xs text-gray-500 font-semibold truncate w-24">{{ $cita->client->primer_nombre }}</p>
                </div>
                <div class="text-right flex items-center gap-2">
                    @if(in_array($cita->estado, ['pendiente', 'confirmada']) && $puedeCompletarse)
                        <form action="{{ route('reservas.completar', $cita) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" title="Completar" class="flex items-center justify-center w-6 h-6 rounded-full bg-green-100 text-green-600 hover:bg-green-600 hover:text-white transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            </button>
                        </form>
                    @endif
                    <span class="text-[9px] uppercase font-bold px-2 py-1 rounded-full {{ $cita->estado == 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 'bg-[#D4AF37] text-black' }}">{{ $cita->estado }}</span>
                </div>
            </li>
        @endforeach
    </ul>
    <div class="mt-4 text-center">
        <a href="{{ route('reservas.index') }}" class="text-[#D4AF37] font-extrabold uppercase text-xs tracking-wider hover:text-black transition">Agenda Completa &rarr;</a>
    </div>
@endif
