<x-admin-layout title="Doctores | ClinicConnect"
                :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Doctores', 'href' => route('admin.doctors.index')],
        ['name' => 'Nuevo'],
    ]">

    {{-- 🔴 DEBUG: Verifica que el usuario llegue --}}
{{-- {{ dd($user) }} --}}

<form action="{{ route('admin.doctors.store') }}" method="POST">
    @csrf

    {{-- ✅ user_id se envía correctamente --}}
    <input type="hidden" name="user_id" value="{{ $user->id }}">

    {{-- 🔹 Header Card --}}
    <x-wire-card class="mb-8">
        <div class="lg:flex lg:justify-between lg:items-center">
            <div class="flex items-center">
                <img src="{{ $user->profile_photo_url }}"
                     class="h-20 w-20 rounded-full">

                <div class="ml-4">
                    <p class="text-2xl font-bold">{{ $user->name }}</p>
                    <p class="text-gray-600">
                        Cédula: {{ $user->id_number ?? 'N/A' }}
                    </p>
                </div>
            </div>

            <div class="flex space-x-3 mt-6 lg:mt-0">
                <x-wire-button outline gray
                               href="{{ route('admin.doctors.index') }}">
                    Volver
                </x-wire-button>

                <x-wire-button type="submit">
                    <i class="fa-solid fa-check"></i> Guardar
                </x-wire-button>
            </div>
        </div>
    </x-wire-card>

    {{-- 🔹 Form Card --}}
    <x-wire-card>

        {{-- ✅ ERRORES --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid md:grid-cols-2 gap-6">

            {{-- Especialidad --}}
            <x-wire-native-select
                label="Especialidad"
                name="specialty_id"
                required
            >
                <option value="">Seleccione una especialidad</option>
                @foreach($specialties as $specialty)
                    <option value="{{ $specialty->id }}"
                        @selected(old('specialty_id') == $specialty->id)>
                        {{ $specialty->name }}
                    </option>
                @endforeach
            </x-wire-native-select>

            {{-- Licencia --}}
            <x-wire-input
                label="Cédula Profesional"
                name="license_number"
                placeholder="Ej: 12345678"
                value="{{ old('license_number') }}"
            />

        </div>

        {{-- Biografía --}}
        <div class="mt-6">
            <x-wire-textarea
                label="Biografía"
                name="biography"
                placeholder="Escriba una breve descripción profesional..."
            >{{ old('biography') }}</x-wire-textarea>
        </div>

    </x-wire-card>
</form>
</x-admin-layout>
