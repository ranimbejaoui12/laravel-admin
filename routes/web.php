<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;

use App\Http\Controllers\UsersController;
use App\Http\Controllers\DoctorsController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\ScansController;
use App\Http\Controllers\OrientationLtrController;
use App\Http\Controllers\PrescriptionsController;

use App\Http\Controllers\Admin\HospitalController;
use App\Http\Controllers\Api\AdminApiController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\VerifyCodeController;

/*
|--------------------------------------------------------------------------
| GUEST ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => view('auth.login'))->middleware('guest');

Route::match(['get','post'], '/login', [AuthController::class, 'login'])
    ->name('login')->middleware('guest');

Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])
    ->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])
    ->name('password.email');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])
    ->name('password.reset');

Route::post('/reset-password', [ResetPasswordController::class, 'store'])
    ->name('password.update');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::post('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    /*
    |--------------------------------------------------------------------------
    | ADMIN ONLY
    |--------------------------------------------------------------------------
    */

    Route::middleware('user-role:ADMIN')->group(function () {

        Route::resource('users', UsersController::class);

        Route::post('/users/find', [UsersController::class, 'findByQuery'])
            ->name('users.findByQuery');

        Route::prefix('admin')->group(function () {
            Route::resource('hospitals', HospitalController::class);
        });
    });

    /*
    |--------------------------------------------------------------------------
    | DOCTOR + ADMIN
    |--------------------------------------------------------------------------
    */

    Route::middleware('user-role:DOCTOR|ADMIN')->group(function () {

        Route::resource('patients', PatientsController::class);
        Route::post('/patients/find', [PatientsController::class, 'findByQuery'])
            ->name('patients.findByQuery');

        Route::resource('doctors', DoctorsController::class);
        Route::resource('scans', ScansController::class);
        Route::resource('orientationLtr', OrientationLtrController::class);
        Route::resource('prescriptions', PrescriptionsController::class);

        Route::get('/prescriptions/{id}/print',
            [PrescriptionsController::class, 'print']
        )->name('prescriptions.print');

        Route::resource('appointments', AppointmentsController::class);

        Route::patch('/appointments/{appointment}/status',
            [AppointmentsController::class, 'updateStatus']
        )->name('appointments.updateStatus');

        Route::get('/appointments-calendar',
            [AppointmentsController::class, 'calendar']
        )->name('appointments.calendar');

        Route::get('/appointments-by-date/{date}',
            [AppointmentsController::class, 'byDate']
        )->name('appointments.byDate');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN ACTIONS (APPROVAL SYSTEM)
    |--------------------------------------------------------------------------
    */

    Route::put('/admin/accept-doctor/{id}', [AdminApiController::class, 'acceptDoctor'])
        ->name('admin.acceptDoctor');

    Route::put('/admin/reject-doctor/{id}', [AdminApiController::class, 'rejectDoctor'])
        ->name('admin.rejectDoctor');

    /*
    |--------------------------------------------------------------------------
    | REQUESTS
    |--------------------------------------------------------------------------
    */

    Route::get('/doctor/requests', [RequestController::class, 'doctorRequests'])
        ->name('requests.doctor');

    Route::patch('/doctor/leave/{id}', [RequestController::class, 'updateDoctorStatusWeb'])
        ->name('doctor.leave.updateWeb');

    Route::patch('/doctor/attestation/{id}', [RequestController::class, 'updateAttestationStatusWeb'])
        ->name('doctor.attestation.updateWeb');

    /*
    |--------------------------------------------------------------------------
    | VERIFY CODE
    |--------------------------------------------------------------------------
    */

    Route::post('/verify-code', [VerifyCodeController::class, 'verifyCode'])
        ->name('verify.code.post');

    Route::get('/verify-code', fn () => view('auth.verify-code'))
        ->name('verify.code');

    Route::get('/reset-password', fn () => view('auth.reset-password'))
        ->name('reset.password.form');
});

Route::post('/appointments/{id}/status', [AppointmentsController::class, 'updateStatus'])
    ->name('appointments.updateStatus');
