<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserRoles;

class PatientApiController extends Controller
{
    // ==============================
    // SIGNUP PATIENT
    // ==============================
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'noSSocial' => 'required|string|max:50|unique:patients,noSSocial',
            'dob' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'lastname' => $request->lastname,
                'username' => $request->username,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => UserRoles::PATIENT,
            ]);

            $patient = Patient::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'lastname' => $request->lastname,
                'username' => $request->username,
                'phone' => $request->phone,
                'email' => $request->email,
                'noSSocial' => $request->noSSocial,
                'dob' => $request->dob,
            ]);

            DB::commit();

            $user->load('patient');

            return response()->json([
                'success' => true,
                'message' => 'Patient registered successfully',
                'user' => $user,
                'patient' => $user->patient,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==============================
    // LOGIN PATIENT
    // ==============================
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::where('email', $request->email)
            ->where('role', UserRoles::PATIENT)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $user->load('patient');

        return response()->json([
            'success' => true,
            'message' => 'Patient logged in successfully',
            'user' => $user,
            'patient' => $user->patient,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 200);
    }

    // ==============================
    // CURRENT PATIENT
    // ==============================
    public function me(Request $request)
    {
        return response()->json($request->user()->patient);
    }

    // ==============================
    // DOCTOR: LIST PATIENTS
    // ==============================
    public function index()
    {
        $doctor = Auth::user();

        if ($doctor->role !== UserRoles::DOCTOR) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $patients = User::where('role', UserRoles::PATIENT->value)->get();

        return response()->json($patients, 200);
    }

    // ==============================
    // UPDATE NOTES (DOCTOR)
    // ==============================
    public function updateNotes(Request $request, $id)
    {
        $doctor = Auth::user();

        if ($doctor->role !== UserRoles::DOCTOR) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $patient = User::find($id);

        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404);
        }

        $patient->notes = $request->notes;
        $patient->save();

        return response()->json([
            'message' => 'Notes updated',
            'notes' => $patient->notes
        ]);
    }

    // ==============================
    // DELETE PATIENT (by patient id)
    // ==============================
    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return response()->json([
            'success' => true,
            'message' => 'Patient deleted successfully'
        ]);
    }

    // ==============================
    // DELETE USER (patient only)
    // ==============================
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->role !== UserRoles::PATIENT) {
            return response()->json([
                'success' => false,
                'message' => 'User is not a patient'
            ], 400);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }

    // ==============================
    // DELETE BY EMAIL
    // ==============================
    public function destroyByEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)
            ->where('role', UserRoles::PATIENT)
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Patient not found'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Patient deleted successfully'
        ]);
    }

    // ==============================
    // PROFILE
    // ==============================
    public function profile(Request $request)
    {
        $user = $request->user();
        $user->load('patient');

        return response()->json([
            'user' => $user,
            'patient' => $user->patient,
        ]);
    }
}