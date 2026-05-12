<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Enums\UserRoles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    // ======================
    // LIST USERS
    // ======================
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    // ======================
    // LIST PATIENTS
    // ======================
    public function patientsList()
    {
        $patients = User::where('role', UserRoles::PATIENT->value)
            ->orderBy('lastname')
            ->get();

        return view('users.patients', compact('patients'));
    }

    // ======================
    // LIST DOCTORS
    // ======================
    public function doctorsList()
    {
        $doctors = User::where('role', UserRoles::DOCTOR->value)
            ->where('status', 'accepted')
            ->orderBy('lastname')
            ->get();

        return view('users.doctors', compact('doctors'));
    }

    // ======================
    // SEARCH DOCTORS
    // ======================
    public function findByQuery(Request $request)
    {
        $result = User::select('id', DB::raw("CONCAT(name,' ',lastname) as text"))
            ->where('role', UserRoles::DOCTOR->value)
            ->where('status', 'accepted')
            ->where(function ($query) use ($request) {
                $query->where('lastname', 'LIKE', '%' . $request->queryTerm . '%')
                      ->orWhere('name', 'LIKE', '%' . $request->queryTerm . '%');
            })
            ->get();

        return response()->json($result);
    }

    // ======================
    // CREATE FORM
    // ======================
    public function create()
    {
        return view('users.create');
    }

    // ======================
    // STORE USER
    // ======================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'role' => 'required|integer',
            'name' => 'required|string',
            'lastname' => 'required|string',
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'required|string',
            'specialty' => 'nullable|string',
            'noSSocial' => 'nullable|string',
            'dob' => 'nullable|date',
            'status' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {

            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'lastname' => $validated['lastname'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'status' => $validated['status'] ?? 'pending',
            ]);

            // Doctor
            if ($validated['role'] == UserRoles::DOCTOR->value) {
                Doctor::create([
                    'user_id' => $user->id,
                    'phone' => $validated['phone'],
                    'specialty' => $validated['specialty'] ?? null,
                ]);
            }

            // Patient
            if ($validated['role'] == UserRoles::PATIENT->value) {
                Patient::create([
                    'user_id' => $user->id,
                    'phone' => $validated['phone'],
                    'dob' => $validated['dob'] ?? null,
                    'noSSocial' => $validated['noSSocial'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }

    // ======================
    // EDIT USER
    // ======================
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // ======================
    // UPDATE USER
    // ======================
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'lastname' => 'required|string',
            'username' => 'required|string|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'phone' => 'nullable|string',
            'specialty' => 'nullable|string',
            'status' => 'nullable|string',
            'dob' => 'nullable|date',
            'noSSocial' => 'nullable|string',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        // Update doctor details
        if ($user->role == UserRoles::DOCTOR->value && $user->doctor) {
            $user->doctor->update([
                'phone' => $validated['phone'] ?? $user->doctor->phone,
                'specialty' => $validated['specialty'] ?? $user->doctor->specialty,
            ]);
        }

        // Update patient details
        if ($user->role == UserRoles::PATIENT->value && $user->patient) {
            $user->patient->update([
                'phone' => $validated['phone'] ?? $user->patient->phone,
                'dob' => $validated['dob'] ?? $user->patient->dob,
                'noSSocial' => $validated['noSSocial'] ?? $user->patient->noSSocial,
            ]);
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    // ======================
    // DELETE USER
    // ======================
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}