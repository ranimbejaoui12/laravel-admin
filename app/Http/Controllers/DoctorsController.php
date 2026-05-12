<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\UserRoles;

class DoctorsController extends Controller
{
    // ===== LIST DOCTORS =====
    public function index()
    {
        $doctors = User::where('role', UserRoles::DOCTOR->value)
            ->where('status', 'accepted')
            ->get();

        return view('doctors.index', compact('doctors'));
    }

    // ===== SHOW CREATE FORM =====
    public function create()
    {
        return view('doctors.create');
    }

    // ===== STORE DOCTOR =====
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'specialty' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:users,email',
        ]);

        User::create([
            'name' => $request->name,
            'specialty' => $request->specialty,
            'phone' => $request->phone,
            'email' => $request->email,
            'role' => UserRoles::DOCTOR->value,
            'status' => 'accepted',
            'password' => bcrypt('password123'),
        ]);

        return redirect()->route('doctors.index')
            ->with('success', 'Doctor added successfully!');
    }

    // ===== SHOW DOCTOR =====
    public function show(User $doctor)
    {
        return view('doctors.show', compact('doctor'));
    }

    // ===== EDIT DOCTOR =====
    public function edit(User $doctor)
    {
        return view('doctors.edit', compact('doctor'));
    }

    // ===== UPDATE DOCTOR =====
    public function update(Request $request, User $doctor)
    {
        $request->validate([
            'name' => 'required',
            'specialty' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:users,email,' . $doctor->id,
        ]);

        $doctor->update(
            $request->only('name', 'specialty', 'phone', 'email')
        );

        return redirect()->route('doctors.index')
            ->with('success', 'Doctor updated successfully!');
    }

    // ===== DELETE DOCTOR =====
    public function destroy(User $doctor)
    {
        $doctor->delete();

        return redirect()->route('doctors.index')
            ->with('success', 'Doctor deleted successfully!');
    }
}