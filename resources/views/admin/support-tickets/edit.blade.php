<x-admin-layout title="Editar ticket | ClinicConnect"
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
        'name' => 'Editar',
    ],
]">

    <x-wire-card>
        <form action="{{ route('admin.support-tickets.update', $supportTicket) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                {{-- Seleccionar usuario --}}
                <x-wire-native-select name="user_id" label="Usuario">
                    <option value="">Seleccionar usuario</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected(old('user_id', $supportTicket->user_id) == $user->id)>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </x-wire-native-select>

                {{-- Prioridad --}}
                <x-wire-native-select name="priority" label="Prioridad">
                    <option value="baja" @selected(old('priority', $supportTicket->priority) == 'baja')>Baja</option>
                    <option value="media" @selected(old('priority', $supportTicket->priority) == 'media')>Media</option>
                    <option value="alta" @selected(old('priority', $supportTicket->priority) == 'alta')>Alta</option>
                </x-wire-native-select>

                {{-- Estado --}}
                <x-wire-native-select name="status" label="Estado">
                    <option value="abierto" @selected(old('status', $supportTicket->status) == 'abierto')>Abierto</option>
                    <option value="en_progreso" @selected(old('status', $supportTicket->status) == 'en_progreso')>En progreso</option>
                    <option value="cerrado" @selected(old('status', $supportTicket->status) == 'cerrado')>Cerrado</option>
                </x-wire-native-select>
            </div>

            {{-- Asunto --}}
            <div class="mb-4">
                <x-wire-input name="subject" label="Asunto" placeholder="Describe brevemente el problema" value="{{ old('subject', $supportTicket->subject) }}" />
            </div>

            {{-- Descripción --}}
            <div class="mb-6">
                <x-wire-textarea name="description" label="Descripción" placeholder="Explica el problema con detalle">{{ old('description', $supportTicket->description) }}</x-wire-textarea>
            </div>

            {{-- Botones --}}
            <div class="flex justify-end space-x-3">
                <x-wire-button outline gray href="{{ route('admin.support-tickets.index') }}">Volver</x-wire-button>
                <x-wire-button type="submit">
                    <i class="fa-solid fa-check mr-1"></i> Guardar cambios
                </x-wire-button>
            </div>
        </form>
    </x-wire-card>

</x-admin-layout>
