{{-- Lógica de PHP para manejar errores y controlar la pestaña activa --}}
@php
    /** @var \Illuminate\Support\ViewErrorBag $errors */

    $errorGroups = [
        'antecedentes' => ['allergies', 'chronic_conditions', 'surgical_history', 'family_medical_history'],
        'informacion-general' => ['blood_type_id', 'observations'],
        'contacto-emergencia' => ['emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relationship'],
    ];

    $initialTab = 'datos-personales';

    foreach ($errorGroups as $tabName => $fields) {
        if ($errors->hasAny($fields)) {
            $initialTab = $tabName;
            break;
        }
    }
@endphp

<x-admin-layout title="Pacientes | ClinicConnect"
                :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Pacientes', 'href' => route('admin.patients.index')],
        ['name' => 'Editar'],
    ]">

    <form action="{{ route('admin.patients.update', $patient) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Encabezado --}}
        <x-wire-card class="mb-8">
            <div class="lg:flex lg:justify-between lg:items-center">
                <div class="flex items-center">
                    <img src="{{ $patient->user->profile_photo_url }}" class="h-20 w-20 rounded-full">
                    <p class="text-2xl font-bold ml-4">{{ $patient->user->name }}</p>
                </div>
                <div class="flex space-x-3 mt-6 lg:mt-0">
                    <x-wire-button outline gray href="{{ route('admin.patients.index') }}">Volver</x-wire-button>
                    <x-wire-button type="submit">
                        <i class="fa-solid fa-check"></i> Guardar cambios
                    </x-wire-button>
                </div>
            </div>
        </x-wire-card>

        <x-wire-card>

            <x-tabs :active="$initialTab">

                {{-- Pestañas --}}
                <x-slot name="header">

                    <x-tab-link tab="datos-personales">
                        <i class="fa-solid fa-user me-2"></i> Datos personales
                    </x-tab-link>

                    <x-tab-link tab="antecedentes" :error="$errors->hasAny($errorGroups['antecedentes'])">
                        <i class="fa-solid fa-file-lines me-2"></i> Antecedentes
                    </x-tab-link>

                    <x-tab-link tab="informacion-general" :error="$errors->hasAny($errorGroups['informacion-general'])">
                        <i class="fa-solid fa-info me-2"></i> Información general
                    </x-tab-link>

                    <x-tab-link tab="contacto-emergencia" :error="$errors->hasAny($errorGroups['contacto-emergencia'])">
                        <i class="fa-solid fa-heart me-2"></i> Contacto de emergencia
                    </x-tab-link>

                </x-slot>

                {{-- Tab1: Datos personales --}}
                <x-tab-content tab="datos-personales">
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded">
                        <div class="flex justify-between">
                            <div>
                                <h3 class="font-bold text-blue-800">Edición de cuenta</h3>
                                <p class="text-blue-600 text-sm">Nombre, email y contraseña se editan en la cuenta.</p>
                            </div>
                            <x-wire-button sm href="{{ route('admin.users.edit', $patient->user) }}" target="_blank">
                                Editar usuario
                            </x-wire-button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div><b>Teléfono:</b> {{ $patient->user->phone }}</div>
                        <div><b>Email:</b> {{ $patient->user->email }}</div>
                        <div><b>Dirección:</b> {{ $patient->user->address }}</div>
                    </div>
                </x-tab-content>

                {{-- Tab2: Antecedentes --}}
                <x-tab-content tab="antecedentes">
                    <div class="grid grid-cols-2 gap-4">
                        <x-wire-textarea label="Alergias conocidas" name="allergies">{{ old('allergies', $patient->allergies) }}</x-wire-textarea>
                        <x-wire-textarea label="Enfermedades crónicas" name="chronic_conditions">{{ old('chronic_conditions', $patient->chronic_conditions) }}</x-wire-textarea>
                        <x-wire-textarea label="Antecedentes quirúrgicos" name="surgical_history">{{ old('surgical_history', $patient->surgical_history) }}</x-wire-textarea>
                        <x-wire-textarea label="Antecedentes familiares" name="family_medical_history">{{ old('family_medical_history', $patient->family_medical_history) }}</x-wire-textarea>
                    </div>
                </x-tab-content>

                {{-- Tab3: Información general --}}
                <x-tab-content tab="informacion-general">
                    <x-wire-native-select name="blood_type_id" label="Tipo de sangre">
                        <option value="">Seleccionar</option>
                        @foreach($bloodTypes as $bloodType)
                            <option value="{{ $bloodType->id }}" @selected(old('blood_type_id', $patient->blood_type_id) == $bloodType->id)>
                                {{ $bloodType->name }}
                            </option>
                        @endforeach
                    </x-wire-native-select>

                    <x-wire-textarea name="observations" label="Observaciones">
                        {{ old('observations', $patient->observations) }}
                    </x-wire-textarea>
                </x-tab-content>

                {{-- Tab4: Contacto de emergencia --}}
                <x-tab-content tab="contacto-emergencia">
                    <x-wire-input label="Nombre de contacto" name="emergency_contact_name" placeholder="Ex: John Doe" value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}" />
                    <x-wire-phone label="Teléfono de contacto" name="emergency_contact_phone" mask="(###) ####-###" placeholder="Ex: (999) 1234-567" value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone)}}" />
                    <x-wire-input label="Relación con el contacto" name="emergency_contact_relationship" placeholder="Familiar, amig@, etc." value="{{ old('emergency_contact_relationship', $patient->emergency_contact_relationship) }}" />
                </x-tab-content>

            </x-tabs>

        </x-wire-card>
    </form>
</x-admin-layout>
