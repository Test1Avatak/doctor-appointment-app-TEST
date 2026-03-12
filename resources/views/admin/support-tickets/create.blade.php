<x-admin-layout title="Nuevo ticket | ClinicConnect"
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
        'name' => 'Nuevo',
    ],
]">

    <x-wire-card>
        <form action="{{ route('admin.support-tickets.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                {{-- Seleccionar usuario --}}
                <x-wire-native-select name="user_id" label="Usuario">
                    <option value="">Seleccionar usuario</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </x-wire-native-select>

                {{-- Prioridad --}}
                <x-wire-native-select name="priority" label="Prioridad">
                    <option value="baja" @selected(old('priority') == 'baja')>Baja</option>
                    <option value="media" @selected(old('priority', 'media') == 'media')>Media</option>
                    <option value="alta" @selected(old('priority') == 'alta')>Alta</option>
                </x-wire-native-select>
            </div>

            {{-- Asunto --}}
            <div class="mb-4">
                <x-wire-input name="subject" label="Asunto" placeholder="Describe brevemente el problema" value="{{ old('subject') }}" />
            </div>

            {{-- Descripción --}}
            <div class="mb-6">
                <x-wire-textarea name="description" label="Descripción" placeholder="Explica el problema con detalle">{{ old('description') }}</x-wire-textarea>
            </div>

            {{-- Botones --}}
            <div class="flex justify-end space-x-3">
                <x-wire-button outline gray href="{{ route('admin.support-tickets.index') }}">Cancelar</x-wire-button>
                <x-wire-button type="submit">
                    <i class="fa-solid fa-check mr-1"></i> Crear ticket
                </x-wire-button>
            </div>
        </form>
    </x-wire-card>

</x-admin-layout>
