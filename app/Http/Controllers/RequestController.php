<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\Attestation;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\NewRequestForAdmin;
use App\Enums\UserRoles;
use App\Notifications\AttestationStatusChanged;
use App\Notifications\LeaveRequestStatusChanged;

class RequestController extends Controller
{
    // =========================================================
    // 🟢 WEB - LIST REQUESTS
    // =========================================================

    public function doctorRequests()
    {
        $doctorId = auth()->id();

        $doctorRequests = LeaveRequest::latest()->paginate(10, ['*'], 'doctor_page');
        $attestations = Attestation::latest()->paginate(10, ['*'], 'attestation_page');

        return view('requests.doctor', compact('doctorRequests', 'attestations', 'doctorId'));
    }

    // =========================================================
    // 🟢 WEB - UPDATE LEAVE REQUEST STATUS
    // =========================================================

    public function updateDoctorStatusWeb(Request $request, $id)
    {
        $request->merge([
            'status' => ucfirst(strtolower($request->status))
        ]);

        $request->validate([
            'status' => 'required|in:Pending,Accepted,Rejected',
        ]);

        $leave = LeaveRequest::findOrFail($id);

        $leave->update([
            'status' => $request->status
        ]);

        // 🔥 NOTIFICATION FIX
        if ($leave->doctor && $leave->doctor->user) {
            $leave->doctor->user->notify(
                new LeaveRequestStatusChanged($leave)
            );
        }

        return back()->with('success', 'Leave request updated successfully');
    }

    // =========================================================
    // 🟢 WEB - UPDATE ATTESTATION STATUS
    // =========================================================

    public function updateAttestationStatusWeb(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:accepted,refused',
        ]);

        $attestation = Attestation::findOrFail($id);

        $attestation->update([
            'status' => $request->status
        ]);

        // 🔥 NOTIFICATION FIX
        if ($attestation->doctor && $attestation->doctor->user) {
            $attestation->doctor->user->notify(
                new AttestationStatusChanged($attestation)
            );
        }

        return back()->with('success', 'Attestation updated successfully');
    }

    // =========================================================
    // 🔵 API - CREATE LEAVE REQUEST
    // =========================================================

    public function storeDoctorRequest(Request $request)
{
    $validated = $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'reason' => 'required|string',
    ]);

    $leave = LeaveRequest::create([
        'doctor_id' => auth()->id(),
        'start_date' => $validated['start_date'],
        'end_date' => $validated['end_date'],
        'reason' => $validated['reason'],
        'status' => 'Pending',
    ]);

    // 🔥 NOTIFY ADMIN
    $admins = User::where('role', UserRoles::ADMIN)->get();

    foreach ($admins as $admin) {
        $admin->notify(new NewRequestForAdmin([
            'type' => 'leave',
            'message' => "New leave request from Doctor ID " . auth()->id(),
            'doctor_id' => auth()->id(),
            'url' => route('requests.doctor') . '?tab=leave'
        ]));
    }

    return response()->json([
        'success' => true,
        'data' => $leave
    ], 201);
}

    // =========================================================
    // 🔵 API - CREATE ATTESTATION
    // =========================================================

    public function storeAttestationRequest(Request $request)
{
    $validated = $request->validate([
        'type' => 'required|string',
        'note' => 'nullable|string',
    ]);

    $attestation = Attestation::create([
        'doctor_id' => auth()->id(),
        'type' => $validated['type'],
        'note' => $request->note ?? '',
        'status' => 'pending',
        'url' => route('requests.doctor') . '?tab=attestation'
    ]);

    // 🔥 NOTIFY ADMIN
    $admins = User::where('role', UserRoles::ADMIN)->get();

    foreach ($admins as $admin) {
        $admin->notify(new NewRequestForAdmin([
            'type' => 'attestation',
            'message' => "New attestation request from Doctor ID " . auth()->id(),
            'doctor_id' => auth()->id(),
        ]));
    }

    return response()->json([
        'success' => true,
        'data' => $attestation
    ], 201);
}
    // =========================================================
    // 🔵 API - GET MY LEAVES
    // =========================================================

    public function myLeaveRequests()
    {
        return response()->json([
            'requests' => LeaveRequest::where('doctor_id', auth()->id())
                ->latest()
                ->get()
        ]);
    }

    // =========================================================
    // 🔵 API - GET MY ATTESTATIONS
    // =========================================================

    public function myAttestations()
    {
        return response()->json([
            'attestations' => Attestation::where('doctor_id', auth()->id())
                ->latest()
                ->get()
        ]);
    }
}
