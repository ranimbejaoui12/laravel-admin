<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Attestation;
use App\Models\LeaveRequest;
use App\Enums\UserRoles;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ======================
        // USERS
        // ======================
        $doctors  = User::where('role', UserRoles::DOCTOR)->count();
        $patients = User::where('role', UserRoles::PATIENT)->count();

        // ======================
        // APPOINTMENTS
        // ======================
        $appointmentsTotal     = Appointment::count();
        $appointmentsConfirmed = Appointment::where('status', 'confirmed')->count();
        $appointmentsPending   = Appointment::where('status', 'pending')->count();
        $appointmentsCanceled  = Appointment::where('status', 'canceled')->count();

        $appointmentsConfirmedPercent = $appointmentsTotal
            ? round(($appointmentsConfirmed / $appointmentsTotal) * 100, 2) : 0;

        $appointmentsPendingPercent = $appointmentsTotal
            ? round(($appointmentsPending / $appointmentsTotal) * 100, 2) : 0;

        $appointmentsCanceledPercent = $appointmentsTotal
            ? round(($appointmentsCanceled / $appointmentsTotal) * 100, 2) : 0;         

        // ======================
        // SMART HOSPITAL MODULE
        // ======================
        $leaveRequests = LeaveRequest::count();
        $attestations  = Attestation::count();

        return view('dashboard', compact(
            'patients',
            'doctors',
            'appointmentsTotal',
            'appointmentsConfirmed',
            'appointmentsPending',
            'appointmentsCanceled',
            'appointmentsConfirmedPercent',
            'appointmentsPendingPercent',
            'appointmentsCanceledPercent',
            'leaveRequests',
            'attestations'
        ));
    }
}