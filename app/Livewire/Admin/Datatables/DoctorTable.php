<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Doctor;
use Illuminate\Database\Eloquent\Builder;

class DoctorTable extends DataTableComponent
{
    protected $model = Doctor::class;

    public function builder(): Builder
    {
        return Doctor::query()
            ->with(['user', 'specialty']);
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

            Column::make('Nombre', 'user.name')
                ->sortable(),

            Column::make('Email', 'user.email')
                ->sortable(),

            Column::make('Especialidad', 'specialty.name')
                ->sortable(),

            Column::make('Cédula Profesional', 'license_number')
                ->sortable()
                ->format(fn($value) => $value ?: 'N/A'),

            Column::make('Biografía', 'biography')
                ->format(fn($value) => $value ?: 'N/A'),

            Column::make('Acciones')
                ->label(function ($row) {
                    return view('admin.doctors.actions', [
                        'doctor' => $row
                    ]);
                }),
        ];
    }
}
