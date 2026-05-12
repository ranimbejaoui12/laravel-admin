<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialty',
        'phone',
        'email',
        'specialty_id',
    ];

    // ================= RELATION TO USER =================
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ================= SPECIALTY =================
    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    // ================= APPOINTMENTS =================
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id', 'id');
    }

    // ================= LEAVE REQUESTS =================
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class, 'doctor_id', 'id');
    }

    // ================= ATTESTATIONS =================
    public function attestations()
    {
        return $this->hasMany(Attestation::class, 'doctor_id', 'id');
    }

    // ================= NOTIFICATIONS CHANNEL =================
    public function routeNotificationForBroadcast()
    {
        return 'doctor.' . $this->user_id;
    }
}