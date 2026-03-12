<x-admin-layout title="Nueva Cita | ClinicConnect"
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
        'name' => 'Nuevo',
    ]
]">

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 max-w-4xl mx-auto">
        <h2 class="text-2xl font-semibold mb-6">Crear Nueva Cita Médica</h2>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc px-4 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.appointments.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Select Paciente -->
                <div>
                    <label class="block font-medium text-gray-700 mb-1">Paciente <span class="text-red-500">*</span></label>
                    <select name="patient_id" required class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full">
                        <option value="">Seleccione un paciente</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
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
                            <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->user->name }} - {{ $doctor->specialty->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Fecha -->
                <div>
                    <label class="block font-medium text-gray-700 mb-1">Fecha de la Cita <span class="text-red-500">*</span></label>
                    <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full">
                </div>
                
                <!-- Helper vacío para empujar los siguientes a nueva línea -->
                <div class="hidden md:block"></div>

                <!-- Hora de Inicio -->
                <div>
                    <label class="block font-medium text-gray-700 mb-1">Hora de Inicio <span class="text-red-500">*</span></label>
                    <input type="time" name="start_time" value="{{ old('start_time', '08:00') }}" required class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full">
                </div>

                <!-- Hora de Fin -->
                <div>
                    <label class="block font-medium text-gray-700 mb-1">Hora de Fin <span class="text-red-500">*</span></label>
                    <input type="time" name="end_time" value="{{ old('end_time', '08:15') }}" required class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full">
                </div>
            </div>

            <!-- Motivo -->
            <div class="mb-6">
                <label class="block font-medium text-gray-700 mb-1">Motivo / Notas de la Cita (Opcional)</label>
                <textarea name="reason" rows="3" class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full">{{ old('reason') }}</textarea>
            </div>

            <div class="flex justify-end gap-2 border-t pt-4">
                <a href="{{ route('admin.appointments.index') }}" class="py-2 px-4 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 transition">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition shadow-md">
                    Guardar Cita
                </button>
            </div>
        </form>
    </div>

</x-admin-layout>
