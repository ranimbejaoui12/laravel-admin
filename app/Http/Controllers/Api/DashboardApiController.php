<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;

class DashboardApiController extends Controller
{
    public function index(Request $request)
    {
        $doctorId = auth()->id();

        // 🔹 Upcoming Appointments (today + future)
        $appointments = Appointment::with('patient')
            ->where('doctor_id', $doctorId)
            ->whereDate('date', '>=', Carbon::today())
            ->orderBy('date', 'asc')
            ->get();

        // 🔹 Next Appointment
        $nextAppointment = Appointment::with('patient')
            ->where('doctor_id', $doctorId)
            ->whereDate('date', '>=', Carbon::today())
            ->orderBy('date', 'asc')
            ->first();

        // 🔹 Total Patients (unique)
        $totalPatients = User::where('role', 'patient')
            ->whereHas('appointments', function ($q) use ($doctorId) {
                $q->where('doctor_id', $doctorId);
            })
            ->count();

        // 🔹 Appointments This Week
        $appointmentsWeek = Appointment::where('doctor_id', $doctorId)
            ->whereBetween('date', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])
            ->count();

        // 🔹 Fake Notifications (later DB)
        $notifications = [
            [
                "type" => "appointment",
                "message" => "New appointment booked"
            ],
            [
                "type" => "cancel",
                "message" => "Appointment cancelled"
            ]
        ];

        // 🔹 Chart (appointments per day)
        $chart = Appointment::selectRaw('DATE(date) as day, COUNT(*) as total')
            ->where('doctor_id', $doctorId)
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        return response()->json([
            "success" => true,

            // 📅 appointments list
            "upcomingAppointments" => $appointments,

            // ⭐ next appointment
            "nextAppointment" => $nextAppointment,

            // 📊 stats
            "stats" => [
                "patients" => $totalPatients,
                "appointments_week" => $appointmentsWeek,
            ],

            // 🔔 notifications
            "notifications" => $notifications,

            // 📈 chart
            "chart" => $chart
        ]);
    }
}
