<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Hospital;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'motivation',
        'date',
        'start_time',
        'end_time',
        'status',
        'user_id',
        'hospital_id',
        'is_new',
    ];

    protected $casts = [
        'is_new' => 'boolean',
        'date' => 'date',
    ];

    // Doctor
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    // Patient
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    // Creator
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Hospital
    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}