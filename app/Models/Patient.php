<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    // ======================
    // MASS ASSIGNABLE
    // ======================
    protected $fillable = [
        'user_id',
        'name',
        'lastname',
        'username',
        'noSSocial',
        'dob',
        'phone',
        'email',
        'diseases',
        'allergies',
        'background',
    ];

    // ======================
    // USER RELATION
    // ======================
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ======================
    // APPOINTMENTS
    // ======================
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    // ======================
    // SCANS
    // ======================
    public function scans()
    {
        return $this->hasMany(Scan::class);
    }

    // ======================
    // ORIENTATION LETTERS
    // ======================
    public function orientationLtrs()
    {
        return $this->hasMany(OrientationLetter::class);
    }

    // ======================
    // PRESCRIPTIONS
    // ======================
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    // ======================
    // DOCTORS (MANY TO MANY)
    // ======================
    public function doctors()
    {
        return $this->belongsToMany(
            User::class,
            'doctor_patient',
            'patient_id',
            'user_id'
        );
    }

    
}