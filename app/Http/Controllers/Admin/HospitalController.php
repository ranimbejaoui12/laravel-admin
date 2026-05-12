<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hospital;
use Illuminate\Support\Facades\Storage;

class HospitalController extends Controller
{
    public function index()
    {
        $hospitals = Hospital::all();
        return view('admin.hospitals.index', compact('hospitals'));
    }

    public function create()
    {
        return view('admin.hospitals.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->only(['name', 'address']);

        // upload logo
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time() . '.' . $file->getClientOriginalExtension();

            // نحطوها في storage باش يكون clean
            $path = $file->storeAs('public/hospital_logos', $filename);

            $data['logo'] = basename($path);
        }

        Hospital::create($data);

        return redirect()->route('hospitals.index')
            ->with('success', 'Hospital added successfully!');
    }

    public function edit(Hospital $hospital)
    {
        return view('admin.hospitals.edit', compact('hospital'));
    }

    public function update(Request $request, Hospital $hospital)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only('name', 'address');

        if ($request->hasFile('logo')) {

            // delete old logo
            if ($hospital->logo) {
                Storage::delete('public/hospital_logos/' . $hospital->logo);
            }

            $file = $request->file('logo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/hospital_logos', $filename);

            $data['logo'] = basename($path);
        }

        $hospital->update($data);

        return redirect()->route('hospitals.index')
            ->with('success', 'Hospital updated successfully!');
    }

    // ✅ API (مفيدة للmobile)
    public function getHospitals()
    {
        $hospitals = Hospital::all();

        return response()->json([
            'data' => $hospitals
        ]);
    }

    public function destroy(Hospital $hospital)
    {
        if ($hospital->logo) {
            Storage::delete('public/hospital_logos/' . $hospital->logo);
        }

        $hospital->delete();

        return redirect()->route('hospitals.index')
            ->with('success', 'Hospital deleted successfully!');
    }
}
