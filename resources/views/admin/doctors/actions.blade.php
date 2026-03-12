<div class="flex items-center space-x-2">

    <!-- Botón Editar (Azul) -->
    <a href="{{ route('admin.doctors.edit', $doctor) }}" class="inline-flex items-center justify-center w-8 h-8 bg-blue-500 rounded text-white hover:bg-blue-600 transition" title="Editar">
        <i class="fa-solid fa-pen-to-square text-sm"></i>
    </a>

    <!-- Botón Horarios (Verde) -->
    <a href="{{ route('admin.doctors.schedules.index', $doctor) }}" class="inline-flex items-center justify-center w-8 h-8 bg-green-500 rounded text-white hover:bg-green-600 transition" title="Gestor de Horarios">
        <i class="fa-solid fa-clock text-sm"></i>
    </a>

</div>
