<x-admin-layout title="Editar Cita | ClinicConnect"
                :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Citas',
        'href' => route('admin.appointments.index'),
    ],
    [
        'name' => 'Editar',
    ]
]">

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 max-w-4xl mx-auto">
        <h2 class="text-2xl font-semibold mb-6">Editar Cita #{{ $appointment->id }}</h2>

        <form action="{{ route('admin.appointments.update', $appointment) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Select Paciente -->
                <div>
                    <label class="block font-medium text-gray-700 mb-1">Paciente <span class="text-red-500">*</span></label>
                    <select name="patient_id" required class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full">
                        <option value="">Seleccione un paciente</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id', $appointment->patient_id) == $patient->id ? 'selected' : '' }}>
                                {{ $patient->user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Select Doctor -->
                <div>
                    <label class="block font-medium text-gray-700 mb-1">Doctor <span class="text-red-500">*</span></label>
                    <select name="doctor_id" required class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full">
                        <option value="">Seleccione un doctor</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ old('doctor_id', $appointment->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->user->name }} - {{ $doctor->specialty->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Fecha -->
                <div>
                    <label class="block font-medium text-gray-700 mb-1">Fecha de la Cita <span class="text-red-500">*</span></label>
                    <input type="date" name="date" value="{{ old('date', \Carbon\Carbon::parse($appointment->date)->format('Y-m-d')) }}" required class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full">
                </div>
                
                <!-- Helper vacío para empujar los siguientes a nueva línea -->
                <div class="hidden md:block"></div>

                <!-- Hora de Inicio -->
                <div>
                    <label class="block font-medium text-gray-700 mb-1">Hora de Inicio <span class="text-red-500">*</span></label>
                    <input type="time" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($appointment->start_time)->format('H:i')) }}" required class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full">
                </div>

                <!-- Hora de Fin -->
                <div>
                    <label class="block font-medium text-gray-700 mb-1">Hora de Fin <span class="text-red-500">*</span></label>
                    <input type="time" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($appointment->end_time)->format('H:i')) }}" required class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full">
                </div>
            </div>

            <div class="mb-6 border-t pt-6">
                <label class="block font-medium text-gray-700 mb-1">Estado <span class="text-red-500">*</span></label>
                <select name="status" class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full md:w-1/2" required>
                    <option value="1" {{ $appointment->status == 1 ? 'selected' : '' }}>Programado</option>
                    <option value="2" {{ $appointment->status == 2 ? 'selected' : '' }}>Completado</option>
                    <option value="3" {{ $appointment->status == 3 ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block font-medium text-gray-700 mb-1">Motivo / Notas de la Cita</label>
                <textarea name="reason" rows="4" class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full">{{ old('reason', $appointment->reason) }}</textarea>
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.appointments.index') }}" class="py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 transition">Cancelar</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition text-sm">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>

</x-admin-layout>
