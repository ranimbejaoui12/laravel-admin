<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Enums\UserRoles;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewRequestForAdmin;

class DoctorApiController extends Controller
{
    // ================= REGISTER DOCTOR =================
    public function signup(Request $request)
    {
        try {

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'lastname' => 'nullable|string|max:255',
                'username' => 'nullable|string|max:255',
                'specialty' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
            ]);

            $user = new User();
            $user->name = $validated['name'];
            $user->lastname = $validated['lastname'] ?? null;
            $user->username = $validated['username'] ?? null;
            $user->specialty = $validated['specialty'];
            $user->phone = $validated['phone'];
            $user->email = $validated['email'];
            $user->password = Hash::make($validated['password']);
            $user->role = UserRoles::DOCTOR;
            $user->status = 'pending';

            if ($request->hasFile('image')) {
                $user->image = $request->file('image')->store('doctors');
            }

            $user->save();

            // ================= NOTIFY ADMINS =================
            $admins = User::where('role', UserRoles::ADMIN)->get();

            foreach ($admins as $admin) {
                $admin->notify(new NewRequestForAdmin([
                    'type' => 'doctor_register',
                    'title' => 'New Doctor Registration',
                    'message' => $user->name . ' requested to join the platform',
                    'doctor_id' => $user->id,
                    'url' => route('users.index')
                ]));
            }

            return response()->json([
                'message' => 'Signup successful, waiting admin approval',
                'user' => $user
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ================= LOGIN DOCTOR =================
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        $doctor = User::where('email', $validated['email'])
            ->where('role', UserRoles::DOCTOR)
            ->first();

        if (!$doctor || !Hash::check($validated['password'], $doctor->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if ($doctor->status == 'pending') {
            return response()->json(['message' => 'Account pending approval'], 403);
        }

        if ($doctor->status == 'rejected') {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $token = $doctor->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login success',
            'doctor' => $doctor,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    // ================= UPDATE PROFILE =================
    public function updateProfile(Request $request)
    {
        $doctor = Auth::user();

        if ($doctor->role !== UserRoles::DOCTOR) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $doctor->id,
            'phone' => 'sometimes|string|max:20',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        if (isset($validated['name'])) $doctor->name = $validated['name'];
        if (isset($validated['email'])) $doctor->email = $validated['email'];
        if (isset($validated['phone'])) $doctor->phone = $validated['phone'];

        if ($request->hasFile('image')) {
            $doctor->image = $request->file('image')->store('doctors');
        }

        $doctor->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'doctor' => $doctor
        ]);
    }

    // ================= LIST ACCEPTED DOCTORS =================
    public function listAcceptedDoctors()
    {
        return User::where('role', UserRoles::DOCTOR)
            ->where('status', 'accepted')
            ->get();
    }

    // ================= MY PATIENTS =================
    public function myPatients()
    {
        $doctor = Auth::user();

        if ($doctor->role !== UserRoles::DOCTOR) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($doctor->patients()->get());
    }
}