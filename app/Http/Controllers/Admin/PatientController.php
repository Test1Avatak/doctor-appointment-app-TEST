<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\BloodType;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Mostrar lista de pacientes
     */
    public function index()
    {
        return view('admin.patients.index');
    }

    /**
     * Mostrar formulario para crear paciente
     */
    public function create()
    {
        return view('admin.patients.create');
    }

    /**
     * Guardar paciente (no se usa directamente porque se crea desde UserController)
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Mostrar detalle del paciente
     */
    public function show(Patient $patient)
    {
        return view('admin.patients.show', compact('patient'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Patient $patient)
    {
        $bloodTypes = BloodType::all();

        return view('admin.patients.edit', compact('patient', 'bloodTypes'));
    }

    /**
     * Actualizar información del paciente
     */
    public function update(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'allergies' => 'nullable|string|max:1000',
            'chronic_conditions' => 'nullable|string|max:1000',
            'surgical_history' => 'nullable|string|max:1000',
            'family_medical_history' => 'nullable|string|max:1000',
            'blood_type_id' => 'nullable|exists:blood_types,id',
            'observations' => 'nullable|string|max:1000',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:255',
        ]);

        $patient->update($data);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Paciente actualizado correctamente',
            'text'  => 'La información médica ha sido actualizada exitosamente'
        ]);

        return redirect()->route('admin.patients.edit', $patient);
    }

    /**
     * Eliminar paciente
     */
    public function destroy(Patient $patient)
    {
        $patient->delete();

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Paciente eliminado correctamente',
            'text'  => 'El paciente ha sido eliminado exitosamente'
        ]);

        return redirect()->route('admin.patients.index');
    }
}
