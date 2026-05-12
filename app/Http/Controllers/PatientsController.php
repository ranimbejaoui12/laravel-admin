<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientFormRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Enums\UserRoles;
use Illuminate\Support\Facades\DB;

class PatientsController extends Controller
{
    // ======================
    // LIST PATIENTS
    // ======================
    public function index()
    {
        $patients = User::where('role', UserRoles::PATIENT->value)
            ->orderBy('name')
            ->get();

        return view('patients.index', compact('patients'));
    }

    // ======================
    // SEARCH PATIENTS
    // ======================
    public function findByQuery(Request $request)
    {
        $queryTerm = $request->query('queryTerm');

        $result = User::select(
                'id',
                DB::raw("CONCAT(name,' ',COALESCE(lastname,'')) as text")
            )
            ->where('role', UserRoles::PATIENT->value)
            ->where(function ($q) use ($queryTerm) {
                $q->where('name', 'LIKE', "%$queryTerm%")
                  ->orWhere('lastname', 'LIKE', "%$queryTerm%");
            })
            ->get();

        return response()->json($result);
    }

    // ======================
    // CREATE FORM
    // ======================
    public function create()
    {
        return view('patients.create');
    }

    // ======================
    // STORE PATIENT
    // ======================
    public function store(PatientFormRequest $request)
    {
        $validated = $request->validated();

        $patient = User::create([
            'name'     => $validated['name'],
            'lastname' => $validated['lastname'] ?? null,
            'email'    => $validated['email'],
            'phone'    => $validated['phone'] ?? null,
            'password' => Hash::make('default123'),
            'role'     => UserRoles::PATIENT->value,
        ]);

        return redirect()
            ->route('patients.index')
            ->with('success', 'Patient ' . $patient->name . ' is created!');
    }

    // ======================
    // SHOW PATIENT
    // ======================
    public function show(User $patient)
    {
        $doctor_id = Auth::id();

        $appointments = $patient->appointments()
            ->where('user_id', $doctor_id)
            ->get();

        $orientationLtrs = $patient->orientationLtrs()
            ->where('user_id', $doctor_id)
            ->get();

        $prescriptions = $patient->prescriptions()
            ->where('user_id', $doctor_id)
            ->get();

        $scans = $patient->scans()
            ->where('user_id', $doctor_id)
            ->get();

        return view('patients.show', compact(
            'patient',
            'appointments',
            'prescriptions',
            'scans',
            'orientationLtrs'
        ));
    }

    // ======================
    // EDIT
    // ======================
    public function edit(User $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    // ======================
    // UPDATE
    // ======================
    public function update(PatientFormRequest $request, User $patient)
    {
        $patient->update($request->validated());

        return back()->with('success', 'Patient ' . $patient->name . ' is updated!');
    }

    // ======================
    // DELETE
    // ======================
    public function destroy(User $patient)
    {
        $patient->delete();

        return redirect()
            ->route('patients.index')
            ->with('success', 'Patient deleted successfully!');
    }
}