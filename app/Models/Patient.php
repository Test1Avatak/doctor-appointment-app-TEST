<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable =[
        'allergies',
        'chronic_conditions',
        'surgical_history',
        'family_medical_history',
        'observations',
        'blood_type_id',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
];
    // Relación uno a uno a la inversa
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // R
    public function bloodType()
    {
        return $this->belongsTo(BloodType::class);
    }
}
