<x-admin-layout title="Tickets de soporte | ClinicConnect"
                :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Tickets de soporte',
    ]
]">

    {{-- Botón para crear nuevo ticket --}}
    <x-slot name="action">
        <x-wire-button href="{{ route('admin.support-tickets.create') }}">
            <i class="fa-solid fa-plus mr-1"></i> Nuevo ticket
        </x-wire-button>
    </x-slot>

    @livewire('admin.datatables.support-ticket-table')

</x-admin-layout>
