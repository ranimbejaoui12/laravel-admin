<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewAppointmentNotification;
use App\Enums\UserRoles;

class AppointmentApiController extends Controller
{
    // =========================================
    // 🔹 PATIENT: REQUEST APPOINTMENT
    // =========================================
    public function requestAppointment(Request $request)
    {
        try {

            $request->validate([

                'hospital_id' => 'required|integer|exists:hospitals,id',

                'doctor_id' => [
                    'nullable',
                    Rule::exists('users', 'id')->where(function ($query) {
                        $query->where('role', UserRoles::DOCTOR);
                    }),
                ],

                'motivation' => 'required|string|min:3|max:1000',

                'appointment_date' => 'required|date',

                'start_time' => 'required',

                'end_time' => 'required',
            ]);

            $user = $request->user();

            // check auth
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // only patient
            if ($user->role !== UserRoles::PATIENT) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only patients can request appointments'
                ], 403);
            }

            // doctor limit (20/day)
            if ($request->doctor_id) {

                $doctorAppointmentsCount = Appointment::where('doctor_id', $request->doctor_id)
                    ->where(function ($query) use ($request) {

                        $query->where('appointment_date', $request->appointment_date)
                              ->orWhere('date', $request->appointment_date);

                    })
                    ->count();

                if ($doctorAppointmentsCount >= 20) {

                    return response()->json([
                        'success' => false,
                        'message' => 'Doctor already has 20 appointments for this day'
                    ], 400);
                }
            }

            // create appointment
            $appointment = Appointment::create([

                'hospital_id' => $request->hospital_id,

                'doctor_id' => $request->doctor_id,

                'patient_id' => $user->id,

                'user_id' => $user->id,

                'motivation' => $request->motivation,

                // support both columns
                'appointment_date' => $request->appointment_date,
                'date' => $request->appointment_date,

                'start_time' => $request->start_time,

                'end_time' => $request->end_time,

                'status' => 'pending',

                'is_new' => 1,
            ]);

            // relations
            $appointment->load([
                'doctor',
                'patient',
                'hospital'
            ]);

            // notify admins + doctors
            $adminsAndDoctors = User::whereIn('role', [
                UserRoles::ADMIN,
                UserRoles::DOCTOR
            ])->get();

            if ($adminsAndDoctors->count() > 0) {

                Notification::send(
                    $adminsAndDoctors,
                    new NewAppointmentNotification($appointment)
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Appointment request sent successfully',
                'data' => $appointment
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to request appointment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // =========================================
    // 🔹 PATIENT: MY APPOINTMENTS
    // =========================================
    public function getAppointments(Request $request)
    {
        try {

            $user = $request->user();

            if (!$user) {

                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated'
                ], 401);
            }

            $appointments = Appointment::where('patient_id', $user->id)
                ->with([
                    'doctor',
                    'hospital'
                ])
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'count' => $appointments->count(),
                'data' => $appointments
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to get appointments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // =========================================
    // 🔹 DOCTOR: MY APPOINTMENTS
    // =========================================
    public function index(Request $request)
    {
        try {

            $user = $request->user();

            if (!$user) {

                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated'
                ], 401);
            }

            // doctor only
            if ($user->role !== UserRoles::DOCTOR) {

                return response()->json([
                    'success' => false,
                    'message' => 'Only doctors allowed'
                ], 403);
            }

            $appointments = Appointment::where('doctor_id', $user->id)
                ->with([
                    'patient',
                    'hospital'
                ])
                ->orderBy('appointment_date', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $appointments
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to get doctor appointments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // =========================================
    // 🔹 ADMIN: GET DOCTOR APPOINTMENTS
    // =========================================
    public function getDoctorAppointments(Request $request, $doctorId)
    {
        try {

            $user = $request->user();

            if ($user->role !== UserRoles::ADMIN) {

                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $appointments = Appointment::where('doctor_id', $doctorId)
                ->with([
                    'patient',
                    'hospital'
                ])
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'data' => $appointments
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to get doctor appointments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // =========================================
    // 🔹 SHOW ONE APPOINTMENT
    // =========================================
    public function show($id)
    {
        try {

            $appointment = Appointment::with([
                'doctor',
                'patient',
                'hospital'
            ])->find($id);

            if (!$appointment) {

                return response()->json([
                    'success' => false,
                    'message' => 'Appointment not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $appointment
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to get appointment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // =========================================
    // 🔹 RESPONSES FOR PATIENT + DOCTOR
    // =========================================
    public function getResponses(Request $request)
    {
        try {

            $user = $request->user();

            if (!$user) {

                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated'
                ], 401);
            }

            $appointments = Appointment::where(function ($query) use ($user) {

                    $query->where('patient_id', $user->id)
                          ->orWhere('doctor_id', $user->id);

                })
                ->with([
                    'doctor:id,name,email',
                    'patient:id,name,email',
                    'hospital:id,name'
                ])
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'data' => $appointments
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to get responses',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // =========================================
    // 🔹 PATIENT: CANCEL APPOINTMENT
    // =========================================
    public function cancelAppointment(Request $request, $id)
    {
        try {

            $user = $request->user();

            $appointment = Appointment::where('id', $id)
                ->where('patient_id', $user->id)
                ->first();

            if (!$appointment) {

                return response()->json([
                    'success' => false,
                    'message' => 'Appointment not found'
                ], 404);
            }

            if ($appointment->status !== 'pending') {

                return response()->json([
                    'success' => false,
                    'message' => 'Only pending appointments can be cancelled'
                ], 400);
            }

            $appointment->update([
                'status' => 'cancelled',
                'is_new' => 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Appointment cancelled successfully',
                'data' => $appointment
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel appointment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // =========================================
    // 🔹 ADMIN / DOCTOR: UPDATE STATUS
    // =========================================
    public function updateStatus(Request $request, $id)
    {
        try {

            $request->validate([
                'status' => 'required|in:pending,confirmed,cancelled,completed,rejected'
            ]);

            $user = $request->user();

            // only admin or doctor
            if (
                $user->role !== UserRoles::ADMIN &&
                $user->role !== UserRoles::DOCTOR
            ) {

                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $appointment = Appointment::find($id);

            if (!$appointment) {

                return response()->json([
                    'success' => false,
                    'message' => 'Appointment not found'
                ], 404);
            }

            $appointment->update([
                'status' => $request->status,
                'is_new' => 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Appointment updated successfully',
                'data' => $appointment
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to update appointment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // =========================================
    // 🔹 CREATE APPOINTMENT DIRECTLY
    // =========================================
    public function createAppointment(Request $request)
    {
        try {

            $request->validate([

                'hospital_id' => 'required|exists:hospitals,id',

                'doctor_id' => 'required|exists:users,id',

                'motivation' => 'required|string|min:3|max:1000',

                'appointment_date' => 'required|date',

                'start_time' => 'required',

                'end_time' => 'required',
            ]);

            // doctor limit
            $doctorAppointmentsCount = Appointment::where('doctor_id', $request->doctor_id)
                ->where('appointment_date', $request->appointment_date)
                ->count();

            if ($doctorAppointmentsCount >= 20) {

                return response()->json([
                    'success' => false,
                    'message' => 'Doctor already has 20 appointments for this day'
                ], 400);
            }

            $appointment = Appointment::create([

                'hospital_id' => $request->hospital_id,

                'doctor_id' => $request->doctor_id,

                'patient_id' => auth()->id(),

                'user_id' => auth()->id(),

                'motivation' => $request->motivation,

                'appointment_date' => $request->appointment_date,

                'date' => $request->appointment_date,

                'start_time' => $request->start_time,

                'end_time' => $request->end_time,

                'status' => 'pending',

                'is_new' => 1,
            ]);

            $appointment->load([
                'doctor',
                'patient',
                'hospital'
            ]);

            return response()->json([
                'success' => true,
                'appointment' => $appointment
            ], 201);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to create appointment',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
