<div>
    <div class="bg-white p-6 shadow-xl sm:rounded-lg mb-6 text-sm">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Buscar disponibilidad</h2>
        <p class="text-gray-500 mb-6">Encuentra el horario perfecto para tu cita.</p>

        <form wire:submit.prevent="searchAvailability" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1">
                <label class="block font-medium text-gray-700 mb-1">Fecha</label>
                <input type="date" wire:model="date"
                    class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full" required>
            </div>
            
            <div class="flex-1">
                <label class="block font-medium text-gray-700 mb-1">Hora</label>
                <select wire:model="time" class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full">
                    <option value="">Selecciona una hora</option>
                    @foreach($timeSlots as $slot)
                        <option value="{{ $slot }}">{{ $slot }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1">
                <label class="block font-medium text-gray-700 mb-1">Especialidad (opcional)</label>
                <select wire:model="specialty_id" class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full">
                    <option value="">Selecciona una especialidad</option>
                    @foreach($specialties as $specialty)
                        <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-md transition disabled:opacity-50" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="searchAvailability">Buscar disponibilidad</span>
                    <span wire:loading wire:target="searchAvailability">Buscando...</span>
                </button>
            </div>
        </form>
    </div>

    @if($hasSearched)
        <div class="bg-white p-6 shadow-xl sm:rounded-lg mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                Doctores Disponibles ({{ count($availableDoctors) }})
            </h3>

            @if(count($availableDoctors) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($availableDoctors as $doctor)
                        <div class="border rounded-lg p-4 flex flex-col items-center text-center {{ $selectedDoctorId == $doctor->id ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-500' : 'border-gray-200 hover:border-blue-300' }} cursor-pointer"
                             wire:click="selectDoctor({{ $doctor->id }})">
                            <img class="w-20 h-20 rounded-full mb-3 object-cover" src="{{ $doctor->user->profile_photo_url }}" alt="{{ $doctor->user->name }}">
                            <h4 class="font-bold text-gray-800">{{ $doctor->user->name }}</h4>
                            <span class="text-sm bg-blue-100 text-blue-800 py-1 px-3 rounded-full mt-2 font-medium">
                                {{ $doctor->specialty->name }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-yellow-50 text-yellow-800 p-4 rounded text-center">
                    No se encontraron doctores disponibles para esta fecha y hora.
                </div>
            @endif
        </div>
    @endif

    @if($selectedDoctorId)
        <div class="bg-white p-6 shadow-xl sm:rounded-lg">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Completar Reservación</h3>

            @if (session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form wire:submit.prevent="bookAppointment">
                @if(!$time)
                    <div class="mb-4">
                        <label class="block font-medium text-gray-700 mb-1">Hora de la cita <span class="text-red-500">*</span></label>
                        <select wire:model="time" class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full md:w-1/3" required>
                            <option value="">Selecciona la hora</option>
                            @foreach($timeSlots as $slot)
                                <option value="{{ $slot }}">{{ $slot }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Como no seleccionaste una hora en la búsqueda, por favor asegúrate de verificar la disponibilidad.</p>
                    </div>
                @endif

                <div class="mb-4">
                    <label class="block font-medium text-gray-700 mb-1">Paciente <span class="text-red-500">*</span></label>
                    <select wire:model="patient_id" class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full" required>
                        <option value="">Selecciona el paciente</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->user->name }} - {{ $patient->user->email }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block font-medium text-gray-700 mb-1">Notas (opcional)</label>
                    <textarea wire:model="notes" rows="3" class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full"></textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-md transition shadow-md">
                        Confirmar y Reservar Cita
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>
