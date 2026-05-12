<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'start_date',
        'end_date',
        'reason',
        'status',
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}