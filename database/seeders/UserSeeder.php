<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds
     */
    public function run(): void
    {
        //Crear un usuario de prueba cada que ejecuto migrations
        User::factory()->create([
            'name' => 'Charly Martinez',
            'email' => 'carlos.martinez@tecdesoftware.com',
            'password' => Hash::make('123456'), // ✅
            'id_number' => '12',
            'phone' => '9911250030',
            'address' => 'Calle Falsa 123',
        ])->assignRole('Doctor');
    }
}
