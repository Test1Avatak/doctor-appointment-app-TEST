<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        return view('admin.doctors.index');
    }

    // Formulario para crear doctor + opciones
    public function create()
    {
        $specialties = Specialty::all();
        return view('admin.doctors.create', compact('specialties'));
    }

// Formulario desde usuario
    public function createFromUser(User $user)
    {
        $specialties = Specialty::all();
        return view('admin.doctors.create', compact('user', 'specialties'));
    }

    // 🔹 GUARDAR DOCTOR
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'        => 'required|exists:users,id',
            'specialty_id'   => 'required|exists:specialties,id',
            'license_number' => 'nullable|string|max:50',
            'biography'      => 'nullable|string',
        ]);

        Doctor::create($data);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Doctor creado correctamente',
            'text'  => 'El doctor fue registrado exitosamente'
        ]);

        return redirect()->route('admin.doctors.index');
    }

    public function edit(Doctor $doctor)
    {
        $specialties = Specialty::all();

        return view('admin.doctors.edit', compact('doctor', 'specialties'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $data = $request->validate([
            'specialty_id'   => 'required|exists:specialties,id',
            'license_number' => 'nullable|string|max:50',
            'biography'      => 'nullable|string',
        ]);

        $doctor->update($data);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Doctor actualizado correctamente',
            'text'  => 'Información guardada exitosamente'
        ]);

        return redirect()->route('admin.doctors.edit', $doctor);
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Doctor eliminado',
            'text'  => 'El doctor fue eliminado correctamente'
        ]);

        return redirect()->route('admin.doctors.index');
    }
}
