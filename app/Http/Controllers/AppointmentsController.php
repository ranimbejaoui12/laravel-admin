<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Enums\UserRoles;
use Illuminate\Http\Request;

class AppointmentsController extends Controller
{
    // ===== LIST ALL APPOINTMENTS =====
    public function index()
{
    $appointments = Appointment::with([
        'doctor',
        'patient',
        'user'
    ])->get();

    $doctors = User::where('role', UserRoles::DOCTOR->value)
        ->where('status', 'accepted')
        ->get();

    $patients = User::where('role', UserRoles::PATIENT->value)->get();

    $hospitals = \App\Models\Hospital::all(); // مهم!

    return view('appointments.index', compact(
        'appointments',
        'doctors',
        'patients',
        'hospitals'
    ));
}

    // ===== SHOW CREATE FORM =====
    public function create()
    {
        $doctors = User::where('role', UserRoles::DOCTOR->value)
            ->where('status', 'accepted')
            ->get();

        $patients = User::where('role', UserRoles::PATIENT->value)->get();

        return view('appointments.create', compact('doctors', 'patients'));
    }

    // ===== STORE APPOINTMENT =====
    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'patient_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'motivation' => 'required|string',
        ]);

        Appointment::create([
            'doctor_id' => $request->doctor_id,
            'patient_id' => $request->patient_id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'motivation' => $request->motivation,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment created successfully!');
    }

    // ===== EDIT FORM =====
    public function edit(Appointment $appointment)
    {
        $doctors = User::where('role', UserRoles::DOCTOR->value)
            ->where('status', 'accepted')
            ->get();

        $patients = User::where('role', UserRoles::PATIENT->value)->get();

        return view('appointments.edit', compact('appointment', 'doctors', 'patients'));
    }

    // ===== UPDATE APPOINTMENT =====
    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'patient_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'motivation' => 'required|string',
        ]);

        $doctor = User::where('id', $request->doctor_id)
            ->where('role', UserRoles::DOCTOR->value)
            ->where('status', 'accepted')
            ->firstOrFail();

        $appointment->update([
            'doctor_id' => $doctor->id,
            'patient_id' => $request->patient_id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'motivation' => $request->motivation,
        ]);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment updated successfully!');
    }

    // ===== DELETE =====
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment deleted successfully!');
    }

    // ===== CALENDAR =====
    public function calendar()
    {
        $appointments = Appointment::all();

        $events = [];

        foreach ($appointments as $appointment) {
            $events[] = [
                'title' => 'Appointment',
                'start' => $appointment->date,
                'color' => 'red',
                'url' => route('appointments.byDate', $appointment->date),
            ];
        }

        return view('appointments.calendar', compact('events'));
    }

    // ===== UPDATE STATUS =====
    public function updateStatus(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $appointment->status = $request->status;
        $appointment->save();

        return redirect()->back()
            ->with('success', 'Appointment status updated!');
    }

    // ===== FILTER BY DATE =====
    public function byDate($date)
    {
        $appointments = Appointment::where('date', $date)->get();

        return view('appointments.by-date', compact('appointments', 'date'));
    }
}
