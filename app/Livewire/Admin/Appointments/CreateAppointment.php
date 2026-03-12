<?php

namespace App\Livewire\Admin\Appointments;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\Patient;
use Carbon\Carbon;
use Livewire\Component;

class CreateAppointment extends Component
{
    public $date;
    public $time = '';
    public $specialty_id = '';

    public $availableDoctors = [];
    public $hasSearched = false;

    // Booking form
    public $selectedDoctorId = '';
    public $patient_id = '';
    public $notes = '';

    public function mount()
    {
        $this->date = date('Y-m-d');
    }

    public function searchAvailability()
    {
        $this->validate([
            'date' => 'required|date',
        ]);

        $dayOfWeek = strtolower(Carbon::parse($this->date)->englishDayOfWeek);
        $timeQuery = $this->time ?: null;

        $query = Doctor::with('user', 'specialty')
            ->whereHas('schedules', function ($q) use ($dayOfWeek, $timeQuery) {
                $q->where('day_of_week', $dayOfWeek);
                if ($timeQuery) {
                    $q->where('start_time', '<=', $timeQuery)
                      ->where('end_time', '>', $timeQuery);
                }
            });

        if ($this->specialty_id) {
            $query->where('specialty_id', $this->specialty_id);
        }

        $potentialDoctors = $query->get();

        $this->availableDoctors = [];

        foreach ($potentialDoctors as $doctor) {
            if ($timeQuery) {
                // Verify no overlapping appointment
                // This checks if the requested time falls within an existing appointment
                $hasAppointment = Appointment::where('doctor_id', $doctor->id)
                    ->where('date', $this->date)
                    ->where('start_time', '<=', $timeQuery)
                    ->where('end_time', '>', $timeQuery)
                    ->whereIn('status', ['Programado'])
                    ->exists();

                if (!$hasAppointment) {
                    $this->availableDoctors[] = $doctor;
                }
            } else {
                $this->availableDoctors[] = $doctor;
            }
        }

        $this->hasSearched = true;
    }

    public function selectDoctor($doctorId)
    {
        $this->selectedDoctorId = $doctorId;
    }

    public function bookAppointment()
    {
        $this->validate([
            'selectedDoctorId' => 'required',
            'patient_id' => 'required',
            'date' => 'required|date',
            'time' => 'required',
        ]);

        $hasAppointment = Appointment::where('doctor_id', $this->selectedDoctorId)
            ->where('date', $this->date)
            ->where('start_time', '<=', $this->time)
            ->where('end_time', '>', $this->time)
            ->whereIn('status', ['Programado'])
            ->exists();

        if ($hasAppointment) {
            session()->flash('error', 'El doctor ya tiene una cita en ese horario.');
            return;
        }

        $endTime = Carbon::parse($this->time)->addMinutes(30)->format('H:i');

        Appointment::create([
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->selectedDoctorId,
            'date' => $this->date,
            'start_time' => $this->time,
            'end_time' => $endTime,
            'status' => 'Programado',
            'notes' => $this->notes,
        ]);

        return redirect()->route('admin.appointments.index')->with('success', 'Cita reservada con éxito.');
    }

    public function render()
    {
        $specialties = Specialty::all();
        $patients = Patient::with('user')->get();

        $timeSlots = [];
        for ($h = 8; $h < 18; $h++) {
            foreach (['00', '15', '30', '45'] as $m) {
                $timeSlots[] = sprintf('%02d:%s', $h, $m);
            }
        }

        return view('livewire.admin.appointments.create-appointment', compact('specialties', 'patients', 'timeSlots'));
    }
}
