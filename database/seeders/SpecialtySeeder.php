<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Specialty;

class SpecialtySeeder extends Seeder
{
    public function run(): void
    {
        $specialties = [
            'Cardiología',
            'Pediatría',
            'Dermatología',
            'Neurología',
            'Ginecología',
            'Traumatología',
            'Oncología',
            'Psiquiatría',
            'Veterinaria'
        ];

        foreach ($specialties as $specialty) {
            Specialty::firstOrCreate([
                'name' => $specialty
            ]);
        }
    }
}
