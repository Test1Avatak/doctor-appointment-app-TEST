<?php

namespace App\Livewire\Admin;

use App\Models\Appointment;
use Livewire\Component;

class ConsultationManager extends Component
{
    public Appointment $appointment;

    // Tabs
    public $activeTab = 'consulta';

    // Form fields for Consultation
    public $diagnosis;
    public $treatment;
    public $notes;

    // Form fields for Prescription
    public $prescriptions = [];
    public $medicationName;
    public $medicationDose;
    public $medicationFrequency;

    // Previous Consultations
    public $showHistoryModal = false;
    public $previousConsultations = [];

    // Patient History Modal
    public $showPatientHistoryModal = false;

    public function mount(Appointment $appointment)
    {
        $this->appointment = $appointment;
        
        $this->diagnosis = $appointment->diagnosis;
        $this->treatment = $appointment->treatment;
        $this->notes = $appointment->consultation_notes;
        
        $this->prescriptions = $appointment->prescriptions ?? [];
    }

    public function addMedication()
    {
        $this->resetValidation();
        $targetTab = 'receta';
        $focusField = '';
        $hasErrors = false;

        if (empty(trim($this->medicationName))) {
            $this->addError('medicationName', 'El campo medicamento es obligatorio.');
            $hasErrors = true;
            if (!$focusField) $focusField = 'medicationName';
        }
        if (empty(trim($this->medicationDose))) {
            $this->addError('medicationDose', 'El campo dosis es obligatorio.');
            $hasErrors = true;
            if (!$focusField) $focusField = 'medicationDose';
        }
        if (empty(trim($this->medicationFrequency))) {
            $this->addError('medicationFrequency', 'El campo frecuencia / duración es obligatorio.');
            $hasErrors = true;
            if (!$focusField) $focusField = 'medicationFrequency';
        }

        if ($hasErrors) {
            $this->dispatch('swal', [
                'title' => 'Error',
                'text'  => 'Favor de completar todos los campos',
                'icon'  => 'error',
                'tab'   => $targetTab,
                'focus' => $focusField
            ]);
            return;
        }

        $this->prescriptions[] = [
            'name' => $this->medicationName,
            'dose' => $this->medicationDose,
            'frequency' => $this->medicationFrequency,
        ];

        $this->medicationName = '';
        $this->medicationDose = '';
        $this->medicationFrequency = '';
    }

    public function removeMedication($index)
    {
        unset($this->prescriptions[$index]);
        $this->prescriptions = array_values($this->prescriptions);
    }

    public function openPatientHistoryModal()
    {
        $this->showPatientHistoryModal = true;
    }

    public function closePatientHistoryModal()
    {
        $this->showPatientHistoryModal = false;
    }

    public function openHistoryModal()
    {
        $this->previousConsultations = Appointment::where('patient_id', $this->appointment->patient_id)
            ->where('id', '!=', $this->appointment->id)
            ->where('status', 2) // Completado
            ->orderBy('date', 'desc')
            ->get();
            
        $this->showHistoryModal = true;
    }

    public function closeHistoryModal()
    {
        $this->showHistoryModal = false;
    }

    public function saveConsultation()
    {
        $this->resetValidation();
        $targetTab = '';
        $focusField = '';
        $hasErrors = false;

        if (empty(trim($this->diagnosis))) {
            $this->addError('diagnosis', 'El campo diagnóstico es obligatorio.');
            $hasErrors = true;
            if (!$focusField) { $targetTab = 'consulta'; $focusField = 'diagnosis'; }
        }
        
        if (empty(trim($this->treatment))) {
            $this->addError('treatment', 'El campo tratamiento es obligatorio.');
            $hasErrors = true;
            if (!$focusField) { $targetTab = 'consulta'; $focusField = 'treatment'; }
        }

        $hasInputs = trim($this->medicationName) !== '' || trim($this->medicationDose) !== '' || trim($this->medicationFrequency) !== '';
        $hasCompleteInputs = trim($this->medicationName) !== '' && trim($this->medicationDose) !== '' && trim($this->medicationFrequency) !== '';
        $hasPrescriptions = count($this->prescriptions) > 0;

        // Se requiere receta. Si no hay prescripciones agregadas, o si hay textos a medias, se valida.
        if (!$hasPrescriptions || $hasInputs) {
            if (empty(trim($this->medicationName))) {
                $this->addError('medicationName', 'El campo medicamento es obligatorio.');
                $hasErrors = true;
                if (!$focusField) { $targetTab = 'receta'; $focusField = 'medicationName'; }
            }
            if (empty(trim($this->medicationDose))) {
                $this->addError('medicationDose', 'El campo dosis es obligatorio.');
                $hasErrors = true;
                if (!$focusField) { $targetTab = 'receta'; $focusField = 'medicationDose'; }
            }
            if (empty(trim($this->medicationFrequency))) {
                $this->addError('medicationFrequency', 'El campo frecuencia / duración es obligatorio.');
                $hasErrors = true;
                if (!$focusField) { $targetTab = 'receta'; $focusField = 'medicationFrequency'; }
            }
        }

        if ($hasErrors) {
            // Cambiar de tab si el error no está en el tab actual y se identificó un tab destino
            if ($targetTab !== '' && $this->activeTab !== $targetTab) {
                $this->activeTab = $targetTab;
            }

            $this->dispatch('swal', [
                'title' => 'Error',
                'text'  => 'Favor de completar todos los campos',
                'icon'  => 'error',
                'tab'   => $targetTab,
                'focus' => $focusField
            ]);
            return;
        }

        // Si el doctor escribió un medicamento completamente pero olvidó darle al '+'
        if ($hasCompleteInputs) {
            $this->prescriptions[] = [
                'name' => $this->medicationName,
                'dose' => $this->medicationDose,
                'frequency' => $this->medicationFrequency,
            ];
            $this->medicationName = '';
            $this->medicationDose = '';
            $this->medicationFrequency = '';
        }

        $this->appointment->update([
            'diagnosis' => $this->diagnosis,
            'treatment' => $this->treatment,
            'consultation_notes' => $this->notes,
            'prescriptions' => $this->prescriptions,
            'status' => 2, // 2 = Completado
        ]);

        session()->flash('success', 'Consulta guardada correctamente.');
        return redirect()->route('admin.appointments.index');
    }

    public function render()
    {
        return view('livewire.admin.consultation-manager')
            ->layout('layouts.admin', [
                'title' => 'Consulta | ClinicConnect',
                'breadcrumbs' => [
                    [
                        'name' => 'Dashboard',
                        'href' => route('admin.dashboard'),
                    ],
                    [
                        'name' => 'Citas',
                        'href' => route('admin.appointments.index'),
                    ],
                    [
                        'name' => 'Consulta',
                    ]
                ]
            ]);
    }
}
