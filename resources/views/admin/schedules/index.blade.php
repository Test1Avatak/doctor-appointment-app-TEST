<x-admin-layout title="Horarios de {{ $doctor->user->name }}"
    :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route('admin.dashboard'),
        ],
        [
            'name' => 'Doctores',
            'href' => route('admin.doctors.index'),
        ],
        [
            'name' => 'Horarios',
        ]
    ]">

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 relative">
        <form action="{{ route('admin.doctors.schedules.store', $doctor) }}" method="POST" id="schedule-form">
            @csrf

            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Gestor de horarios</h2>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition duration-200 shadow-sm" id="save-btn">
                    Guardar horario
                </button>
            </div>

            <!-- Responsive Table Wrapper -->
            <div class="overflow-x-auto w-full">
                <table class="w-full text-sm text-left text-gray-600 min-w-max border-t border-gray-200">
                    <thead class="text-xs text-gray-400 uppercase bg-white font-semibold">
                        <tr>
                            <th scope="col" class="px-6 py-4 border-b">DÍA/HORA</th>
                            @foreach ($days as $day)
                                <th scope="col" class="px-6 py-4 border-b text-center">{{ strtoupper($day) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($hours as $hour)
                            @php
                                $hourCarbon = \Carbon\Carbon::createFromFormat('H:i', $hour);
                                $slots = [
                                    $hourCarbon->format('H:i'),
                                    $hourCarbon->copy()->addMinutes(15)->format('H:i'),
                                    $hourCarbon->copy()->addMinutes(30)->format('H:i'),
                                    $hourCarbon->copy()->addMinutes(45)->format('H:i'),
                                ];
                            @endphp
                            <tr class="border-b bg-white hover:bg-gray-50 transition-colors">
                                <!-- Columna de Hora Principal -->
                                <th scope="row" class="px-6 py-4 font-bold text-gray-800 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <input type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 mr-3 select-all-row" data-target=".row-{{ str_replace(':', '', $hour) }}">
                                        {{ $hour }}:00
                                    </div>
                                </th>

                                <!-- Columnas por Día -->
                                @foreach ($days as $day)
                                    <td class="px-6 py-4 align-top">
                                        <div class="flex flex-col space-y-2">
                                            <!-- Checkbox "Todos" -->
                                            <div class="flex items-center font-semibold text-gray-700 mb-1">
                                                <input type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 mr-2 select-all-cell" data-target=".cell-{{ $day }}-{{ str_replace(':', '', $hour) }}">
                                                <label class="cursor-pointer text-xs">Todos</label>
                                            </div>
                                            
                                            <!-- 4 checkboxes de 15 mins -->
                                            @foreach ($slots as $slot)
                                                @php
                                                    $endSlot = \Carbon\Carbon::createFromFormat('H:i', $slot)->addMinutes(15)->format('H:i');
                                                    $isChecked = isset($activeSlots[$day]) && in_array($slot, $activeSlots[$day]);
                                                @endphp
                                                <div class="flex items-center text-xs text-gray-500">
                                                    <input type="checkbox" name="slots[{{ $day }}][]" value="{{ $slot }}" 
                                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 mr-2 row-{{ str_replace(':', '', $hour) }} cell-{{ $day }}-{{ str_replace(':', '', $hour) }} slot-checkbox"
                                                        {{ $isChecked ? 'checked' : '' }}>
                                                    <label class="cursor-pointer">{{ $slot }} - {{ $endSlot }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // Lógica para "Todos" de una celda (Día + Horario global)
            const cellSelectAllChecks = document.querySelectorAll('.select-all-cell');
            cellSelectAllChecks.forEach(check => {
                check.addEventListener('change', function() {
                    const targetClass = this.getAttribute('data-target');
                    const checkboxes = document.querySelectorAll(targetClass);
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    updateRowCheckboxState(this);
                });
            });

            // Lógica para el checkbox de la fila completa (La Hora a la izquierda)
            const rowSelectAllChecks = document.querySelectorAll('.select-all-row');
            rowSelectAllChecks.forEach(check => {
                check.addEventListener('change', function() {
                    const targetClass = this.getAttribute('data-target');
                    const checkboxes = document.querySelectorAll(targetClass);
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    
                    // Update cell "Todos" checks within this row
                    const rowClassSegment = targetClass.replace('.row-', '');
                    const cellSelects = document.querySelectorAll('.select-all-cell[data-target*="' + rowClassSegment + '"]');
                    cellSelects.forEach(cb => cb.checked = this.checked);
                });
            });

            // Lógica para desmarcar "Todos" si desmarcas uno individual
            const slotCheckboxes = document.querySelectorAll('.slot-checkbox');
            slotCheckboxes.forEach(check => {
                check.addEventListener('change', function() {
                    updateParentCheckboxesState(this);
                });
            });
            
            function updateParentCheckboxesState(checkbox) {
                // Determine which cell select all to update based on classes
                const classes = Array.from(checkbox.classList);
                let cellClass = classes.find(c => c.startsWith('cell-'));
                if(cellClass) {
                    const groupCheckboxes = document.querySelectorAll('.' + cellClass + '.slot-checkbox');
                    const allChecked = Array.from(groupCheckboxes).every(cb => cb.checked);
                    const cellSelectTarget = document.querySelector('.select-all-cell[data-target=".' + cellClass + '"]');
                    if(cellSelectTarget) cellSelectTarget.checked = allChecked;
                }
            }

            // Alerta bonita de SWAL al guardar
            @if(session('success'))
                Swal.fire({
                    title: '¡Éxito!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#3085d6',
                });
            @endif
        });
    </script>
    @endpush
</x-admin-layout>
