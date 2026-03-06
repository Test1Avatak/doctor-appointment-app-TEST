@php
    $priorityColors = [
        'baja' => 'bg-green-100 text-green-800',
        'media' => 'bg-yellow-100 text-yellow-800',
        'alta' => 'bg-red-100 text-red-800',
    ];
    $statusColors = [
        'abierto' => 'bg-blue-100 text-blue-800',
        'en_progreso' => 'bg-orange-100 text-orange-800',
        'cerrado' => 'bg-gray-100 text-gray-800',
    ];
    $priorityLabels = ['baja' => 'Baja', 'media' => 'Media', 'alta' => 'Alta'];
    $statusLabels = ['abierto' => 'Abierto', 'en_progreso' => 'En progreso', 'cerrado' => 'Cerrado'];
@endphp

<x-admin-layout title="Detalle del ticket | ClinicConnect"
                :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Tickets de soporte',
        'href' => route('admin.support-tickets.index'),
    ],
    [
        'name' => 'Detalle',
    ],
]">

    <x-wire-card>
        {{-- Encabezado con acciones --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">Ticket #{{ $supportTicket->id }}</h2>
            <div class="flex space-x-3">
                <x-wire-button outline gray href="{{ route('admin.support-tickets.index') }}">Volver</x-wire-button>
                <x-wire-button href="{{ route('admin.support-tickets.edit', $supportTicket) }}">
                    <i class="fa-solid fa-pen-to-square mr-1"></i> Editar
                </x-wire-button>
            </div>
        </div>

        {{-- Información del ticket --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500 mb-1">Asunto</p>
                <p class="text-lg font-semibold text-gray-800">{{ $supportTicket->subject }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500 mb-1">Usuario</p>
                <p class="text-lg text-gray-800">{{ $supportTicket->user->name }} ({{ $supportTicket->user->email }})</p>
            </div>

            <div>
                <p class="text-sm text-gray-500 mb-1">Prioridad</p>
                <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $priorityColors[$supportTicket->priority] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ $priorityLabels[$supportTicket->priority] ?? $supportTicket->priority }}
                </span>
            </div>

            <div>
                <p class="text-sm text-gray-500 mb-1">Estado</p>
                <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $statusColors[$supportTicket->status] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ $statusLabels[$supportTicket->status] ?? $supportTicket->status }}
                </span>
            </div>

            <div>
                <p class="text-sm text-gray-500 mb-1">Fecha de creación</p>
                <p class="text-gray-800">{{ $supportTicket->created_at->format('d/m/Y H:i') }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500 mb-1">Última actualización</p>
                <p class="text-gray-800">{{ $supportTicket->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        {{-- Descripción --}}
        <div class="mt-6">
            <p class="text-sm text-gray-500 mb-1">Descripción</p>
            <div class="bg-gray-50 rounded-lg p-4 text-gray-800">
                {{ $supportTicket->description }}
            </div>
        </div>
    </x-wire-card>

</x-admin-layout>
