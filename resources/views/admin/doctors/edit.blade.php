<x-admin-layout title="Editar Doctor | ClinicConnect"
                :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Doctores', 'href' => route('admin.doctors.index')],
        ['name' => 'Editar'],
    ]">

    <form action="{{ route('admin.doctors.update', $doctor) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Encabezado --}}
        <x-wire-card class="mb-8">
            <div class="lg:flex lg:justify-between lg:items-center">
                <div class="flex items-center">
                    <img src="{{ $doctor->user->profile_photo_url }}"
                         class="h-20 w-20 rounded-full">
                    <p class="text-2xl font-bold ml-4">
                        {{ $doctor->user->name }}
                    </p>
                </div>

                <div class="flex space-x-3 mt-6 lg:mt-0">
                    <x-wire-button outline gray
                                   href="{{ route('admin.doctors.index') }}">
                        Volver
                    </x-wire-button>

                    <x-wire-button type="submit">
                        <i class="fa-solid fa-check"></i>
                        Guardar cambios
                    </x-wire-button>
                </div>
            </div>
        </x-wire-card>

        {{-- Información --}}
        <x-wire-card>

            <div class="grid grid-cols-2 gap-4">

                <x-wire-native-select label="Especialidad" name="specialty_id">
                    <option value="">Seleccione una especialidad</option>
                    @foreach($specialties as $specialty)
                        <option value="{{ $specialty->id }}"
                            @selected(old('specialty_id', $doctor->specialty_id) == $specialty->id)>
                            {{ $specialty->name }}
                        </option>
                    @endforeach
                </x-wire-native-select>

                <x-wire-input label="Número de licencia"
                              name="license_number"
                              value="{{ old('license_number', $doctor->license_number) }}" />

            </div>

        </x-wire-card>

    </form>
</x-admin-layout>
