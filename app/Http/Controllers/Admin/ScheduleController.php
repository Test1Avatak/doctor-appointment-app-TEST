<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index(Doctor $doctor)
    {
        $days = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
        $hours = [
            '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', 
            '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00'
        ];

        // Format existing schedules into 15 min slots for the view
        $existingSchedules = $doctor->schedules;
        $activeSlots = [];

        foreach ($existingSchedules as $schedule) {
            $start = Carbon::parse($schedule->start_time);
            $end = Carbon::parse($schedule->end_time);

            while ($start->lt($end)) {
                $timeKey = $start->format('H:i');
                $activeSlots[$schedule->day][] = $timeKey;
                $start->addMinutes(15);
            }
        }

        return view('admin.schedules.index', compact('doctor', 'days', 'hours', 'activeSlots'));
    }

    public function store(Request $request, Doctor $doctor)
    {
        $slots = $request->input('slots', []);
        
        // Remove all previous schedules
        $doctor->schedules()->delete();

        $schedulesToInsert = [];

        foreach ($slots as $day => $times) {
            sort($times); // Ensure chronological order

            $currentStart = null;
            $currentEnd = null;

            foreach ($times as $time) {
                // $time looks like "08:15"
                $carbonTime = Carbon::createFromFormat('H:i', $time);
                $slotStart = $carbonTime->format('H:i');
                $slotEnd = $carbonTime->copy()->addMinutes(15)->format('H:i');

                if ($currentStart === null) {
                    $currentStart = $slotStart;
                    $currentEnd = $slotEnd;
                } else {
                    if ($slotStart === $currentEnd) {
                        // Contiguous, extend block
                        $currentEnd = $slotEnd;
                    } else {
                        // Break in continuity, save previous block
                        $schedulesToInsert[] = [
                            'doctor_id' => $doctor->id,
                            'day' => $day,
                            'start_time' => $currentStart . ':00',
                            'end_time' => $currentEnd . ':00',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        // Start new block
                        $currentStart = $slotStart;
                        $currentEnd = $slotEnd;
                    }
                }
            }

            // Save the last block for the day
            if ($currentStart !== null) {
                $schedulesToInsert[] = [
                    'doctor_id' => $doctor->id,
                    'day' => $day,
                    'start_time' => $currentStart . ':00',
                    'end_time' => $currentEnd . ':00',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (count($schedulesToInsert) > 0) {
            Schedule::insert($schedulesToInsert);
        }

        return redirect()->route('admin.doctors.schedules.index', $doctor)
            ->with('success', 'Horario actualizado correctamente.');
    }
}
