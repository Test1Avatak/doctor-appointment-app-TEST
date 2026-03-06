<div class="flex items-center space-x-2">
    {{-- Botón para ver detalle --}}
    <x-wire-button href="{{ route('admin.support-tickets.show', $supportTicket) }}" green xs>
        <i class="fa-solid fa-eye"></i>
    </x-wire-button>

    {{-- Botón para editar --}}
    <x-wire-button href="{{ route('admin.support-tickets.edit', $supportTicket) }}" blue xs>
        <i class="fa-solid fa-pen-to-square"></i>
    </x-wire-button>

    {{-- Botón para eliminar --}}
    <form action="{{ route('admin.support-tickets.destroy', $supportTicket) }}" method="POST" class="delete-form">
        @csrf
        @method('DELETE')
        <x-wire-button type="submit" red xs>
            <i class="fa-solid fa-trash"></i>
        </x-wire-button>
    </form>
</div>
