<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\SupportTicket;
use Illuminate\Database\Eloquent\Builder;

class SupportTicketTable extends DataTableComponent
{
    // Consulta base con relación de usuario
    public function builder(): Builder
    {
        return SupportTicket::query()
            ->with('user');
    }

    protected $model = SupportTicket::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    // Definición de columnas de la tabla
    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->sortable(),
            Column::make('Asunto', 'subject')
                ->sortable()
                ->searchable(),
            Column::make('Usuario', 'user.name')
                ->sortable(),
            Column::make('Prioridad', 'priority')
                ->sortable()
                ->format(function ($value) {
                    $colors = [
                        'baja' => 'bg-green-100 text-green-800',
                        'media' => 'bg-yellow-100 text-yellow-800',
                        'alta' => 'bg-red-100 text-red-800',
                    ];
                    $labels = [
                        'baja' => 'Baja',
                        'media' => 'Media',
                        'alta' => 'Alta',
                    ];
                    $color = $colors[$value] ?? 'bg-gray-100 text-gray-800';
                    $label = $labels[$value] ?? $value;
                    return '<span class="px-2 py-1 text-xs font-semibold rounded-full ' . $color . '">' . $label . '</span>';
                })
                ->html(),
            Column::make('Estado', 'status')
                ->sortable()
                ->format(function ($value) {
                    $colors = [
                        'abierto' => 'bg-blue-100 text-blue-800',
                        'en_progreso' => 'bg-orange-100 text-orange-800',
                        'cerrado' => 'bg-gray-100 text-gray-800',
                    ];
                    $labels = [
                        'abierto' => 'Abierto',
                        'en_progreso' => 'En progreso',
                        'cerrado' => 'Cerrado',
                    ];
                    $color = $colors[$value] ?? 'bg-gray-100 text-gray-800';
                    $label = $labels[$value] ?? $value;
                    return '<span class="px-2 py-1 text-xs font-semibold rounded-full ' . $color . '">' . $label . '</span>';
                })
                ->html(),
            Column::make('Fecha', 'created_at')
                ->sortable()
                ->format(function ($value) {
                    return $value->format('d/m/Y H:i');
                }),
            Column::make('Acciones')
                ->label(function ($row) {
                    return view('admin.support-tickets.actions', ['supportTicket' => $row]);
                }),
        ];
    }
}
