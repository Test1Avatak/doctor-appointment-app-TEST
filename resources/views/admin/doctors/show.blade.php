<x-admin-layout title="Detalle Doctor | ClinicConnect"
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
        'name' => 'Detalle',
    ],
]">

    <x-wire-card>

        <div class="flex items-center mb-6">
            <img src="{{ $doctor->user->profile_photo_url }}"
                 class="h-20 w-20 rounded-full">
            <div class="ml-4">
                <h2 class="text-xl font-bold">
                    {{ $doctor->user->name }}
                </h2>
                <p class="text-gray-500">
                    {{ $doctor->user->email }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div><b>Especialidad:</b> {{ $doctor->specialty->name }}</div>
            <div><b>Licencia:</b> {{ $doctor->license_number }}</div>
        </div>

    </x-wire-card>

</x-admin-layout>
