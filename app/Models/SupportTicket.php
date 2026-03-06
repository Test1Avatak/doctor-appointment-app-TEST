<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    // Campos asignables en masa
    protected $fillable = [
        'user_id',
        'subject',
        'description',
        'priority',
        'status',
    ];

    // Valores posibles de prioridad
    const PRIORITY_LOW = 'baja';
    const PRIORITY_MEDIUM = 'media';
    const PRIORITY_HIGH = 'alta';

    // Valores posibles de estado
    const STATUS_OPEN = 'abierto';
    const STATUS_IN_PROGRESS = 'en_progreso';
    const STATUS_CLOSED = 'cerrado';

    /**
     * Obtener las opciones de prioridad.
     */
    public static function priorities(): array
    {
        return [
            self::PRIORITY_LOW => 'Baja',
            self::PRIORITY_MEDIUM => 'Media',
            self::PRIORITY_HIGH => 'Alta',
        ];
    }

    /**
     * Obtener las opciones de estado.
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_OPEN => 'Abierto',
            self::STATUS_IN_PROGRESS => 'En progreso',
            self::STATUS_CLOSED => 'Cerrado',
        ];
    }

    // Relación: el ticket pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
