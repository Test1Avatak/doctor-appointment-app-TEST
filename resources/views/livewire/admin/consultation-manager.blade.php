<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ $appointment->patient->user->name }}</h2>
            <p class="text-sm text-gray-500">DNI: {{ $appointment->patient->dni }} | Fecha de Nacimiento: {{ $appointment->patient->date_of_birth ? $appointment->patient->date_of_birth->format('d/m/Y') : 'N/A' }}</p>
        </div>
        <div class="flex space-x-2">
            <button wire:click="openPatientHistoryModal" class="py-2 px-4 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 transition flex items-center">
                <i class="fa-solid fa-notes-medical mr-2"></i> Ver Historia
            </button>
            <button wire:click="openHistoryModal" class="py-2 px-4 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 transition flex items-center">
                <i class="fa-solid fa-clock-rotate-left mr-2"></i> Consultas Anteriores
            </button>
        </div>
    </div>

    <!-- MAIN CARD -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <!-- TABS -->
        <div class="border-b border-gray-200">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500">
                <li class="mr-2">
                    <button wire:click="$set('activeTab', 'consulta')" class="inline-flex items-center p-4 border-b-2 rounded-t-lg {{ $activeTab === 'consulta' ? 'text-blue-600 border-blue-600' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}">
                        <i class="fa-solid fa-stethoscope mr-2"></i> Consulta
                    </button>
                </li>
                <li class="mr-2">
                    <button wire:click="$set('activeTab', 'receta')" class="inline-flex items-center p-4 border-b-2 rounded-t-lg {{ $activeTab === 'receta' ? 'text-blue-600 border-blue-600' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}">
                        <i class="fa-solid fa-prescription-bottle-medical mr-2"></i> Receta
                    </button>
                </li>
            </ul>
        </div>

        <div class="p-6">
            <!-- TAB: CONSULTA -->
            @if($activeTab === 'consulta')
                <div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Diagnóstico <span class="text-red-500">*</span></label>
                        <textarea id="diagnosis" wire:model="diagnosis" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Describa el diagnóstico del paciente aquí..."></textarea>
                        @error('diagnosis') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tratamiento <span class="text-red-500">*</span></label>
                        <textarea id="treatment" wire:model="treatment" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Describa el tratamiento recomendado aquí..."></textarea>
                        @error('treatment') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notas Adicionales</label>
                        <textarea wire:model="notes" rows="2" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Agregue notas adicionales sobre la consulta..."></textarea>
                    </div>
                </div>
            @endif

            <!-- TAB: RECETA -->
            @if($activeTab === 'receta')
                <div>
                    <!-- Formulario de agregar medicamento -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                            <div class="md:col-span-4">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Medicamento</label>
                                <input id="medicationName" wire:model="medicationName" type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Ej: Amoxicilina 500mg">
                                @error('medicationName') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="md:col-span-3">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Dosis</label>
                                <input id="medicationDose" wire:model="medicationDose" type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Ej: 1 pastilla">
                                @error('medicationDose') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="md:col-span-4">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Frecuencia / Duración</label>
                                <input id="medicationFrequency" wire:model="medicationFrequency" type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Ej: cada 8 horas por 7 días">
                                @error('medicationFrequency') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="md:col-span-1 flex justify-end pb-1 inline-flex items-center px-4">
                                <button wire:click="addMedication" class="p-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md transition" title="Añadir Medicamento">
                                    <i class="fa-solid fa-plus text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de Medicamentos -->
                    @if(count($prescriptions) > 0)
                        <div class="overflow-x-auto border border-gray-200 rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Medicamento</th>
                                        <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Dosis</th>
                                        <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Frecuencia / Duración</th>
                                        <th class="px-6 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Acción</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($prescriptions as $index => $prescription)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $prescription['name'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $prescription['dose'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $prescription['frequency'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                                <button wire:click="removeMedication({{ $index }})" class="text-red-500 hover:text-red-700 transition" title="Eliminar">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-6 text-gray-500">
                            <i class="fa-solid fa-capsules text-3xl mb-2 text-gray-300"></i>
                            <p>No se han añadido medicamentos a esta receta.</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- FOOTER / BOTÓN GUARDAR -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end">
            <button wire:click="saveConsultation" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow transition flex items-center">
                <i class="fa-solid fa-floppy-disk mr-2"></i> Guardar Consulta
            </button>
        </div>
    </div>


    <!-- MODAL HISTORIAL DE CONSULTAS ANTERIORES -->
    @if($showHistoryModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!-- Background backdrop, show/hide based on modal state. -->
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                <!-- Ocurre el fondo semitransparente -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeHistoryModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-gray-100 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Consultas Anteriores - {{ $appointment->patient->user->name }}
                            </h3>
                            <button wire:click="closeHistoryModal" class="text-gray-400 hover:text-gray-500 hover:bg-gray-100 p-1 rounded-full transition">
                                <i class="fa-solid fa-xmark text-xl"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6 bg-gray-50 max-h-[70vh] overflow-y-auto">
                        @if(count($previousConsultations) > 0)
                            <div class="space-y-6">
                                @foreach($previousConsultations as $past)
                                    <div class="bg-white border rounded-lg shadow-sm overflow-hidden">
                                        <div class="bg-gray-100 px-4 py-3 border-b flex justify-between items-center">
                                            <div>
                                                <span class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($past->date)->format('d \d\e M, Y') }} | {{ \Carbon\Carbon::parse($past->start_time)->format('H:i') }}</span>
                                                <span class="mx-2 text-gray-400">|</span>
                                                <span class="text-sm text-gray-600">Atendido por: {{ $past->doctor->user->name }}</span>
                                            </div>
                                            <a href="{{ route('admin.consultations.show', $past) }}" target="_blank" class="text-sm font-medium text-blue-600 hover:text-blue-800 transition flex items-center">
                                                 Consultar Detalle <i class="fa-solid fa-arrow-up-right-from-square ml-1 text-xs"></i>
                                            </a>
                                        </div>
                                        <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                            <div>
                                                <h4 class="font-semibold text-gray-700 mb-1 border-b pb-1">Diagnóstico</h4>
                                                <p class="text-gray-600">{{ $past->diagnosis ?: 'No especificado' }}</p>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-700 mb-1 border-b pb-1">Tratamiento</h4>
                                                <p class="text-gray-600">{{ $past->treatment ?: 'No especificado' }}</p>
                                            </div>
                                            @if($past->consultation_notes)
                                                <div class="md:col-span-2 mt-2">
                                                    <h4 class="font-semibold text-gray-700 mb-1">Notas:</h4>
                                                    <p class="text-gray-600">{{ $past->consultation_notes }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12 text-gray-500">
                                <i class="fa-solid fa-folder-open text-4xl mb-3 text-gray-300"></i>
                                <p class="text-lg">No hay consultas anteriores registradas para este paciente.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- MODAL DE HISTORIA MÉDICA DEL PACIENTE -->
    @if($showPatientHistoryModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closePatientHistoryModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Historia médica del paciente
                            </h3>
                            <button wire:click="closePatientHistoryModal" class="text-gray-400 hover:text-gray-500 hover:bg-gray-100 p-1 rounded-full transition">
                                <i class="fa-solid fa-xmark text-xl"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6 bg-white">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-sm mb-6 pb-6 border-b border-gray-100">
                            <div>
                                <h4 class="text-gray-500 mb-1">Tipo de sangre:</h4>
                                <p class="font-medium text-gray-900">{{ $appointment->patient->bloodType->name ?? 'No especificado' }}</p>
                            </div>
                            <div>
                                <h4 class="text-gray-500 mb-1">Alergias:</h4>
                                <p class="font-medium text-gray-900">{{ $appointment->patient->allergies ?: 'No registradas' }}</p>
                            </div>
                            <div>
                                <h4 class="text-gray-500 mb-1">Enfermedades crónicas:</h4>
                                <p class="font-medium text-gray-900">{{ $appointment->patient->chronic_conditions ?: 'No registradas' }}</p>
                            </div>
                            <div>
                                <h4 class="text-gray-500 mb-1">Antecedentes quirúrgicos:</h4>
                                <p class="font-medium text-gray-900">{{ $appointment->patient->surgical_history ?: 'No registradas' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <a href="{{ route('admin.patients.show', $appointment->patient) }}" target="_blank" class="text-blue-600 hover:text-blue-800 font-medium text-sm transition">
                                Ver / Editar Historia Médica
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('swal', (event) => {
                const data = Array.isArray(event) ? event[0] : event;
                Swal.fire({
                    title: data.title,
                    text: data.text,
                    icon: data.icon,
                }).then(() => {
                    if (data.focus) {
                        setTimeout(() => {
                            const inputElement = document.getElementById(data.focus);
                            if (inputElement) {
                                inputElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                inputElement.focus();
                                inputElement.classList.add('ring-2', 'ring-red-500', 'border-red-500');
                                setTimeout(() => {
                                    inputElement.classList.remove('ring-2', 'ring-red-500', 'border-red-500');
                                }, 2000);
                            }
                        }, 100);
                    }
                });
            });
        });
    </script>
</div>
