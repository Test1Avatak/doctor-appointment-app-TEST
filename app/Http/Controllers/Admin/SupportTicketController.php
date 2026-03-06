<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    /**
     * Mostrar lista de tickets de soporte.
     */
    public function index()
    {
        return view('admin.support-tickets.index');
    }

    /**
     * Mostrar formulario para crear un nuevo ticket.
     */
    public function create()
    {
        $users = User::orderBy('name')->get();

        return view('admin.support-tickets.create', compact('users'));
    }

    /**
     * Guardar un nuevo ticket de soporte.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'priority' => 'required|in:baja,media,alta',
        ]);

        $data['status'] = 'abierto';

        SupportTicket::create($data);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Ticket creado correctamente',
            'text'  => 'El ticket de soporte ha sido registrado exitosamente',
        ]);

        return redirect()->route('admin.support-tickets.index');
    }

    /**
     * Mostrar el detalle de un ticket de soporte.
     */
    public function show(SupportTicket $supportTicket)
    {
        return view('admin.support-tickets.show', compact('supportTicket'));
    }

    /**
     * Mostrar formulario de edición de un ticket.
     */
    public function edit(SupportTicket $supportTicket)
    {
        $users = User::orderBy('name')->get();

        return view('admin.support-tickets.edit', compact('supportTicket', 'users'));
    }

    /**
     * Actualizar la información de un ticket de soporte.
     */
    public function update(Request $request, SupportTicket $supportTicket)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'priority' => 'required|in:baja,media,alta',
            'status' => 'required|in:abierto,en_progreso,cerrado',
        ]);

        $supportTicket->update($data);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Ticket actualizado correctamente',
            'text'  => 'El ticket de soporte ha sido actualizado exitosamente',
        ]);

        return redirect()->route('admin.support-tickets.edit', $supportTicket);
    }

    /**
     * Eliminar un ticket de soporte.
     */
    public function destroy(SupportTicket $supportTicket)
    {
        $supportTicket->delete();

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Ticket eliminado correctamente',
            'text'  => 'El ticket de soporte ha sido eliminado exitosamente',
        ]);

        return redirect()->route('admin.support-tickets.index');
    }
}
