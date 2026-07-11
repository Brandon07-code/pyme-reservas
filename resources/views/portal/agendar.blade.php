@extends('layouts.client')

@section('title', 'Agendar Cita')

@section('content')
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-2xl overflow-hidden border border-[#D4AF37]/30">
        
        <div class="bg-black p-6 text-center border-b-4 border-[#D4AF37]">
            <h1 class="text-3xl font-extrabold text-white uppercase tracking-widest">Agendar Cita</h1>
            <p class="text-[#D4AF37] font-semibold text-sm mt-1">Sigue los pasos para asegurar tu espacio</p>
        </div>

        <form action="{{ route('portal.store') }}" method="POST" class="p-8">
            @csrf

            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                    <p class="font-bold">Error:</p>
                    <ul class="list-disc ml-5">
                        @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                
                {{-- PASO 1: Servicios --}}
                <div class="md:col-span-2">
                    <h3 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4"><span class="text-[#D4AF37]">1.</span> Selecciona los servicios</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($services as $servicio)
                            <label class="cursor-pointer border border-gray-200 rounded-lg p-3 hover:border-[#D4AF37] hover:bg-gray-50 transition flex items-start">
                                <input type="checkbox" name="servicios[]" value="{{ $servicio->id }}" data-duracion="{{ $servicio->duracion_minutos }}" class="mt-1 h-5 w-5 text-[#D4AF37] border-gray-300 rounded focus:ring-[#D4AF37] checkbox-servicio">
                                <div class="ml-3">
                                    <span class="block text-sm font-bold text-gray-900">{{ $servicio->nombre }}</span>
                                    <span class="block text-xs text-gray-500">${{ number_format($servicio->precio, 0, ',', '.') }} • ⏱ {{ $servicio->duracion_minutos }}m</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- PASO 2: Barbero --}}
                <div>
                    <h3 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4"><span class="text-[#D4AF37]">2.</span> Elige tu Barbero</h3>
                    <select name="employee_id" id="employee_id" required class="w-full border-gray-300 rounded-md shadow-sm p-3 focus:ring-[#D4AF37] focus:border-[#D4AF37]">
                        <option value="">Selecciona un profesional...</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->user->primer_nombre }} {{ $emp->user->primer_apellido }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- PASO 3: Fecha --}}
                <div>
                    <h3 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4"><span class="text-[#D4AF37]">3.</span> Selecciona el Día</h3>
                    <input type="date" name="fecha" id="fecha" required min="{{ date('Y-m-d') }}" class="w-full border-gray-300 rounded-md shadow-sm p-3 focus:ring-[#D4AF37] focus:border-[#D4AF37]">
                </div>

            </div>

            {{-- PASO 4: Horas (Se llenan con JS) --}}
            <div id="contenedor-horas" class="hidden mb-8">
                <h3 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4"><span class="text-[#D4AF37]">4.</span> Selecciona la Hora</h3>
                
                <!-- Este input oculto guardará la hora que el cliente elija al hacer clic en el botón -->
                <input type="hidden" name="hora_inicio" id="hora_inicio" required>
                
                <div id="botones-horas" class="flex flex-wrap gap-3">
                    <!-- Aquí JS pintará los botones amarillos -->
                </div>
                
                <p id="mensaje-error-horas" class="hidden text-red-500 font-bold mt-2"></p>
            </div>

            <div class="flex justify-center pt-6 border-t border-gray-200">
                <button type="submit" id="btn-submit" disabled class="bg-gray-400 text-white font-extrabold py-3 px-12 rounded-full shadow-lg transition uppercase tracking-widest cursor-not-allowed">
                    Confirmar Reserva
                </button>
            </div>
        </form>
    </div>

    <!-- CEREBRO JAVASCRIPT -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.checkbox-servicio');
            const selectBarbero = document.getElementById('employee_id');
            const inputFecha = document.getElementById('fecha');
            const contenedorHoras = document.getElementById('contenedor-horas');
            const botonesHoras = document.getElementById('botones-horas');
            const mensajeErrorHoras = document.getElementById('mensaje-error-horas');
            const inputHoraOculto = document.getElementById('hora_inicio');
            const btnSubmit = document.getElementById('btn-submit');

            // Detectar cambios en cualquier input para buscar horas
            checkboxes.forEach(cb => cb.addEventListener('change', buscarHorasLibres));
            selectBarbero.addEventListener('change', buscarHorasLibres);
            inputFecha.addEventListener('change', buscarHorasLibres);

            function buscarHorasLibres() {
                // Reiniciar estado
                contenedorHoras.classList.add('hidden');
                botonesHoras.innerHTML = '';
                inputHoraOculto.value = '';
                btnSubmit.disabled = true;
                btnSubmit.classList.replace('bg-black', 'bg-gray-400');
                btnSubmit.classList.replace('hover:bg-gray-800', 'cursor-not-allowed');

                // Validar que los 3 pasos iniciales estén llenos
                const barberoId = selectBarbero.value;
                const fecha = inputFecha.value;
                let duracionTotal = 0;
                let serviciosSeleccionados = false;

                checkboxes.forEach(cb => {
                    if (cb.checked) {
                        duracionTotal += parseInt(cb.getAttribute('data-duracion'));
                        serviciosSeleccionados = true;
                    }
                });

                if (!serviciosSeleccionados || !barberoId || !fecha) {
                    return; // Faltan datos, no buscamos horas
                }

                contenedorHoras.classList.remove('hidden');
                botonesHoras.innerHTML = '<span class="text-gray-500 italic">Buscando disponibilidad...</span>';

                // Petición AJAX al servidor de Laravel
                fetch('{{ route('portal.disponibilidad') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        employee_id: barberoId,
                        fecha: fecha,
                        duracion_minutos: duracionTotal
                    })
                })
                .then(response => response.json())
                .then(horas => {
                    botonesHoras.innerHTML = '';
                    
                    if (horas.length === 0) {
                        mensajeErrorHoras.textContent = 'El barbero seleccionado no tiene espacio suficiente en la fecha indicada.';
                        mensajeErrorHoras.classList.remove('hidden');
                        return;
                    }

                    mensajeErrorHoras.classList.add('hidden');

                    // Pintar los botones de horas
                    horas.forEach(hora => {
                        // Formatear a AM/PM para que se vea bonito
                        const date = new Date('2000-01-01T' + hora);
                        const horaFormateada = date.toLocaleTimeString('en-US', {hour: '2-digit', minute:'2-digit'});

                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.textContent = horaFormateada;
                        btn.className = 'hora-btn bg-white border-2 border-gray-300 text-gray-700 font-bold py-2 px-4 rounded-lg hover:border-[#D4AF37] hover:text-[#D4AF37] transition';
                        
                        btn.addEventListener('click', function() {
                            // Quitar selección a todos los botones
                            document.querySelectorAll('.hora-btn').forEach(b => {
                                b.classList.replace('bg-[#D4AF37]', 'bg-white');
                                b.classList.replace('text-black', 'text-gray-700');
                                b.classList.replace('border-[#D4AF37]', 'border-gray-300');
                            });

                            // Marcar el botón clickeado
                            this.classList.replace('bg-white', 'bg-[#D4AF37]');
                            this.classList.replace('text-gray-700', 'text-black');
                            this.classList.replace('border-gray-300', 'border-[#D4AF37]');

                            // Guardar valor para enviar el formulario y encender el botón de enviar
                            inputHoraOculto.value = hora;
                            btnSubmit.disabled = false;
                            btnSubmit.classList.replace('bg-gray-400', 'bg-black');
                            btnSubmit.classList.replace('cursor-not-allowed', 'hover:bg-gray-800');
                        });

                        botonesHoras.appendChild(btn);
                    });
                })
                .catch(error => {
                    botonesHoras.innerHTML = '<span class="text-red-500">Error de conexión. Intente nuevamente.</span>';
                });
            }
        });
    </script>
@endsection