<x-admin-layout title="Doctores | ClinicConnect"
                :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Doctores',
    ]
]">

    @livewire('admin.datatables.doctor-table')

</x-admin-layout>
