<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;

use App\Models\Notification;
use App\Models\Scan;

// Controllers
use App\Http\Controllers\Api\DoctorApiController;
use App\Http\Controllers\Api\PatientApiController;
use App\Http\Controllers\Api\AppointmentApiController;
use App\Http\Controllers\Api\NotificationApiController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\DoctorsController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\Admin\HospitalController;

/*

|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// ================= DOCTOR AUTH =================
Route::prefix('doctor')->group(function () {
    Route::post('/signup', [DoctorApiController::class, 'signup']);
    Route::post('/login', [DoctorApiController::class, 'login']);

    // OTP reset password
    Route::post('/forgot-password', [\App\Http\Controllers\Api\DoctorResetPasswordController::class, 'sendCode']);
    Route::post('/verify-code', [\App\Http\Controllers\Api\DoctorResetPasswordController::class, 'verifyCode']);
    Route::post('/reset-password', [\App\Http\Controllers\Api\DoctorResetPasswordController::class, 'resetWithCode']);
});

// ================= PATIENT AUTH =================
Route::prefix('patient')->group(function () {
    Route::post('/signup', [PatientApiController::class, 'signup']);
    Route::post('/login', [PatientApiController::class, 'login']);
});

// ================= DOCTORS PUBLIC =================
Route::get('/doctors', [DoctorsController::class, 'apiIndex']);
Route::get('/specialties', [DoctorsController::class, 'getSpecialties']);
Route::get('/doctors/filter', [DoctorsController::class, 'filterDoctors']);

// ================= PATIENTS PUBLIC =================
Route::get('/patients', [PatientsController::class, 'index']);
Route::get('/patients/{id}', [PatientsController::class, 'show']);

// ================= HOSPITALS =================
Route::get('/hospitals', [HospitalController::class, 'getHospitals']);

// ================= PASSWORD RESET (PATIENT) =================
Route::prefix('forgot-password')->group(function () {
    Route::post('/send-otp', [PasswordResetController::class, 'sendOtp']);
    Route::post('/verify-otp', [PasswordResetController::class, 'verifyOtp']);
    Route::post('/reset', [PasswordResetController::class, 'resetPassword']);
    Route::post('/resend-otp', [PasswordResetController::class, 'resendOtp']);
});

// ================= SCANS =================
Route::get('/scans/{patient_id}', function ($patient_id) {
    return Scan::where('patient_id', $patient_id)->get()->map(function ($scan) {
        return [
            'id' => $scan->id,
            'type' => $scan->type,
            'url' => config('app.url') . '/storage/' . $scan->scan_path,
            'date' => $scan->created_at,
        ];
    });
});

// ================= TEST =================
Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is working!',
        'timestamp' => now()
    ]);
});

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (SANCTUM)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // CURRENT USER
    Route::get('/user', fn (Request $request) => $request->user());

    // PROFILE PATIENT
    Route::get('/patient/profile', [PatientApiController::class, 'profile']);
    Route::put('/patient/profile', [PatientApiController::class, 'updateProfile']);

    // APPOINTMENTS
    Route::post('/appointments/request', [AppointmentApiController::class, 'requestAppointment']);
    Route::get('/appointments', [AppointmentApiController::class, 'getAppointments']);
    Route::get('/appointments/responses', [AppointmentApiController::class, 'getResponses']);
    Route::get('/appointments/{id}', [AppointmentApiController::class, 'show']);

    Route::post('/appointments/create', [AppointmentApiController::class, 'createAppointment']);
    Route::patch('/appointments/{id}/cancel', [AppointmentApiController::class, 'cancelAppointment']);

    // DOCTOR DASHBOARD APPOINTMENTS
    Route::get('/appointments/test', [AppointmentApiController::class, 'getDoctorsWithAppointments']);
    Route::patch('/appointments/{id}/status', [AppointmentApiController::class, 'updateStatus']);

    // NOTIFICATIONS
    Route::get('/notifications', [NotificationApiController::class, 'index']);
    Route::get('/notifications/{id}', [NotificationApiController::class, 'show']);

    Route::post('/notifications/{id}/read', function ($id) {
        $notification = Notification::findOrFail($id);
        $notification->is_read = true;
        $notification->save();

        return response()->json(['success' => true]);
    });

    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    });

    // DOCTOR PROFILE
    Route::get('/doctor/my-patients', [DoctorApiController::class, 'myPatients']);
    Route::put('/doctor/update-profile', [DoctorApiController::class, 'updateProfile']);

    // ADMIN
    Route::get('/admin/pending-doctors', [\App\Http\Controllers\Api\AdminApiController::class, 'pendingDoctors']);
    Route::put('/admin/accept/{id}', [\App\Http\Controllers\Api\AdminApiController::class, 'acceptDoctor']);
    Route::put('/admin/reject/{id}', [\App\Http\Controllers\Api\AdminApiController::class, 'rejectDoctor']);

    // DASHBOARD
    Route::get('/dashboard', [\App\Http\Controllers\Api\DashboardApiController::class, 'index']);

    // REQUESTS
    Route::post('/leave-request', [\App\Http\Controllers\RequestController::class, 'storeDoctorRequest']);
    Route::get('/my-leaves', [\App\Http\Controllers\RequestController::class, 'myLeaveRequests']);

    Route::post('/attestation', [\App\Http\Controllers\RequestController::class, 'storeAttestationRequest']);
    Route::get('/my-attestations', [\App\Http\Controllers\RequestController::class, 'myAttestations']);
});

// ================= BROADCAST =================
Broadcast::routes(['middleware' => ['auth:sanctum']]);
Route::post('/doctor/forgot-password', [PasswordResetController::class, 'sendOtp']);
Route::post('/doctor/verify-code', [PasswordResetController::class, 'verifyOtp']);
Route::post('/doctor/reset-password', [PasswordResetController::class, 'resetPassword']);
