<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Enums\UserRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // ======================
    // FILLABLE
    // ======================
    protected $fillable = [
        'name',
        'lastname',
        'username',
        'email',
        'password',
        'image',
        'phone',
        'role',
        'status',
        'specialty',
    ];

    // ======================
    // HIDDEN
    // ======================
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // ======================
    // CASTS
    // ======================
    protected $casts = [
        'email_verified_at' => 'datetime',
        'role' => UserRoles::class,
    ];

    // ======================
    // DOCTOR RELATION
    // ======================
    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    // ======================
    // PATIENT RELATION
    // ======================
    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    // ======================
    // APPOINTMENTS AS DOCTOR
    // ======================
    public function doctorAppointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    // ======================
    // APPOINTMENTS AS PATIENT
    // ======================
    public function patientAppointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    // ======================
    // SMART APPOINTMENTS ACCESS
    // ======================
    public function appointments()
    {
        return $this->role === UserRoles::DOCTOR
            ? $this->doctorAppointments()
            : $this->patientAppointments();
    }

    // ======================
    // PRESCRIPTIONS
    // ======================
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'patient_id');
    }

    // ======================
    // SCANS
    // ======================
    public function scans()
    {
        return $this->hasMany(Scan::class, 'patient_id');
    }

    // ======================
    // ORIENTATION LETTERS
    // ======================
    public function orientationLtrs()
    {
        return $this->hasMany(OrientationLetter::class, 'user_id');
    }

    // ======================
    // DOCTOR-PATIENT RELATION (many-to-many)
    // ======================
    public function patients()
    {
        return $this->belongsToMany(
            Patient::class,
            'doctor_patient',
            'user_id',
            'patient_id'
        );
    }

    // ======================
    // SCOPES
    // ======================
    public function scopeDoctors($query)
    {
        return $query->where('role', UserRoles::DOCTOR);
    }

    public function scopePatients($query)
    {
        return $query->where('role', UserRoles::PATIENT);
    }

    // ======================
    // NOTIFICATIONS
    // ======================
    public function notifications()
    {
        return $this->morphMany(
            \Illuminate\Notifications\DatabaseNotification::class,
            'notifiable'
        )->orderBy('created_at', 'desc');
    }

    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }
}