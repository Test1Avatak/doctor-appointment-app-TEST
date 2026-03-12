<div class="flex space-x-2">
    <a href="{{ route('admin.consultations.show', $appointment) }}" class="inline-flex items-center px-2 py-1 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 focus:bg-green-600 active:bg-green-700 transition" title="Consulta">
        <i class="fa-solid fa-stethoscope"></i>
    </a>
    <a href="{{ route('admin.appointments.edit', $appointment) }}" class="inline-flex items-center px-2 py-1 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 focus:bg-blue-600 active:bg-blue-700 transition" title="Editar">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
    <form action="{{ route('admin.appointments.destroy', $appointment) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta cita?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="inline-flex items-center px-2 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 transition" title="Eliminar">
            <i class="fa-solid fa-trash"></i>
        </button>
    </form>
</div>
