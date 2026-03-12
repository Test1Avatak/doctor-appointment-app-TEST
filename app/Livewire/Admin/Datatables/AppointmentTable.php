<?php

namespace App\Livewire\Admin\Datatables;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class AppointmentTable extends DataTableComponent
{
    protected $model = Appointment::class;

    public function builder(): Builder
    {
        return Appointment::query()->with(['patient.user', 'doctor.user']);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable(),

            Column::make('Paciente', 'patient.user.name')
                ->sortable()
                ->searchable(),

            Column::make('Doctor', 'doctor.user.name')
                ->sortable()
                ->searchable(),

            Column::make('Fecha', 'date')
                ->sortable()
                ->format(fn($value) => \Carbon\Carbon::parse($value)->format('d/m/Y')),

            Column::make('Hora inicio', 'start_time')
                ->sortable()
                ->format(fn($value) => \Carbon\Carbon::parse($value)->format('H:i')),

            Column::make('Hora fin', 'end_time')
                ->sortable()
                ->format(fn($value) => \Carbon\Carbon::parse($value)->format('H:i')),

            Column::make('Estado', 'status')
                ->sortable()
                ->format(function ($value) {
                    if ($value == 1) return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Programado</span>';
                    if ($value == 2) return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Completado</span>';
                    if ($value == 0) return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Cancelado</span>';
                    return $value;
                })
                ->html(),

            Column::make('Acciones')
                ->label(function ($row) {
                    return view('admin.appointments.actions', [
                        'appointment' => $row
                    ]);
                }),
        ];
    }
}
