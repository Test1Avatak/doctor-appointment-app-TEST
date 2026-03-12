<x-admin-layout title="Citas | ClinicConnect"
                :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Citas',
    ]
]">

    <div class="mb-4 flex justify-end">
        <a href="{{ route('admin.appointments.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
            <i class="fa-solid fa-plus mr-2"></i> Nuevo
        </a>
    </div>

    @livewire('admin.datatables.appointment-table')

</x-admin-layout>
